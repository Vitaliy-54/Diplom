<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteFile;
use App\Models\Tag;
use App\Models\NoteReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;


class NoteController extends Controller
{
public const MAX_USER_STORAGE = 157286400; // 150MB в байтах

    protected function checkUserStorage($newFilesSize = 0)
    {
        $user = auth()->user();
        if (!$user) return false;
        
        $totalUsed = $user->getTotalStorageUsed();
        $availableSpace = self::MAX_USER_STORAGE - $totalUsed;
        
        return $availableSpace >= $newFilesSize;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tag = $request->input('tag');
        $tab = $request->input('tab', 'all');
        
        $user = auth()->user();
        $isAdmin = $user ? $user->isAdmin() : false;
        
        $query = Note::with(['user', 'tags']);
        
        if ($tab === 'my') {
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                $query->where('id', 0);
            }
        } elseif ($tab === 'private') {
            if ($isAdmin) {
                $query->where('is_public', false);
            } else {
                $query->where('id', 0);
            }
        } else {
            $query->where('is_public', true);
        }
        
        if ($tag) {
            $query->whereHas('tags', function($q) use ($tag) {
                $q->where('id', $tag);
            });
        }
        
        if ($search) {
            $query->where('title', 'like', '%'.$search.'%');
        }
        
        $query->orderBy('created_at', 'DESC');
        
        $notes = $query->paginate(5);
        $allTags = $this->getTagsForCurrentTab($tab, $user, $isAdmin);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('notes.partials.notes_list', [
                    'notes' => $notes,
                    'tab' => $tab, // Явно передаем текущую вкладку
                    'is_ajax' => true // Добавляем флаг AJAX-запроса
                ])->render(),
                'next_page' => $notes->hasMorePages() ? $notes->nextPageUrl() : null,
                'allTags' => $allTags,
                'tab' => $tab
            ]);
        }
        
        return view('notes.index', compact('notes', 'allTags', 'search', 'tag', 'tab'));
    }

    protected function cleanupUnusedTags()
    {
        // Находим теги, которые не используются ни в одной заметке
        $unusedTags = Tag::doesntHave('notes')->get();

        foreach ($unusedTags as $tag) {
            try {
                $tag->delete();
            } catch (\Exception $e) {
                // Логируем ошибку, если не удалось удалить тег
                \Log::error("Failed to delete unused tag {$tag->id}: " . $e->getMessage());
            }
        }

        return $unusedTags->count();
    }

    protected function getTagsForCurrentTab($tab, $user, $isAdmin)
    {
        $tagsQuery = Tag::query()->has('notes');
        
        if ($tab === 'my' && $user) {
            $tagsQuery->whereHas('notes', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($tab === 'private' && $isAdmin) {
            $tagsQuery->whereHas('notes', function($q) {
                $q->where('is_public', false);
            });
        } elseif ($tab === 'all') {
            $tagsQuery->whereHas('notes', function($q) {
                $q->where('is_public', true);
            });
        } else {
            return collect();
        }
        
        return $tagsQuery->orderBy('name')->get();
    }

    public function create()
    {
        $tags = Tag::all();
        return view('notes.create', compact('tags'));
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'is_public' => 'sometimes|boolean',
        'tags' => 'sometimes|array',
        'tags.*' => 'exists:tags,id',
        'files' => 'sometimes|array',
        'files.*' => 'file|max:153600', // 150MB для каждого файла
    ]);

    // Проверка доступного места перед созданием заметки
    if ($request->hasFile('files')) {
        $totalNewFilesSize = array_reduce($request->file('files'), function($carry, $file) {
            return $carry + ($file->isValid() ? $file->getSize() : 0);
        }, 0);

        if (!$this->checkUserStorage($totalNewFilesSize)) {
            return back()->withInput()->with('error', 
                'Недостаточно места для загрузки файлов. Максимальный лимит: 150МБ. ' .
                'Удалите ненужные файлы или освободите место.');
        }
    }

    // Создаем заметку
    $note = Note::create([
        'title' => $request->title,
        'description' => $request->description,
        'is_public' => $request->boolean('is_public'),
        'user_id' => auth()->id(),
    ]);

    // Прикрепляем теги
    if ($request->has('tags')) {
        $note->tags()->sync($request->tags);
    }

    // Обработка загрузки файлов
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            if ($file->isValid()) {
                try {
                    $originalName = $file->getClientOriginalName();
                    $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME))
                                 .'.'.pathinfo($originalName, PATHINFO_EXTENSION);
                    $fileName = time().'_'.$safeName;
                    $storagePath = "notes/{$note->id}/{$fileName}";
                    
                    // Сохраняем файл
                    Storage::disk('local')->put($storagePath, file_get_contents($file->getRealPath()));
                    
                    // Создаем запись о файле
                    $note->files()->create([
                        'name' => $originalName,
                        'path' => $storagePath,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error("File upload error: " . $e->getMessage());
                    continue;
                }
            }
        }
    }

    // Очистка неиспользуемых тегов
    $this->cleanupUnusedTags();

    return redirect()->route('notes.index')
        ->with('success', 'Заметка создана успешно.')
        ->with('storage_updated', true);
}
    
    public function downloadFile(Note $note, NoteFile $file)
    {
        // Проверяем принадлежность файла к заметке
        if ($note->id !== $file->note_id) {
            abort(404);
        }
    
        // Проверяем права доступа
        if (!$note->is_public && $note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
    
        // Проверяем существование файла в хранилище
        if (!Storage::disk('local')->exists($file->path)) {
            abort(404, 'Файл не найден в хранилище');
        }
    
        // Получаем полный путь к файлу
        $filePath = Storage::disk('local')->path($file->path);
        
        // Генерируем ответ для скачивания
        return response()->download(
            $filePath,
            $file->name,
            [
                'Content-Type' => $file->mime_type,
                'Content-Disposition' => 'attachment; filename="' . $file->name . '"'
            ]
        );
    }

    public function destroyFile(Note $note, NoteFile $file)
    {
        if ($note->id !== $file->note_id) {
            abort(404);
        }
    
        if ($note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
    
        // Удаляем файл из хранилища
        Storage::disk('local')->delete($file->path);
    
        // Удаляем запись из БД
        $file->delete();
    
        return back()->with([
            'success' => 'Файл удален успешно.',
            'storage_updated' => true // Флаг для обновления информации о хранилище
        ]);
    }

    public function show(Note $note)
    {
        if (!$note->is_public && $note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещён!');
        }
    
        // Получаем количество реакций
        $reactions = NoteReaction::where('note_id', $note->id)
            ->selectRaw('reaction, count(*) as count')
            ->groupBy('reaction')
            ->pluck('count', 'reaction')
            ->toArray();
    
        $allReactions = [
            'like' => 0,
            'dislike' => 0,
            'heart' => 0,
            'laugh' => 0,
            'wow' => 0
        ];
    
        $reactionsData = array_merge($allReactions, $reactions);
    
        // Получаем реакцию текущего пользователя
        $userReaction = auth()->check() 
            ? NoteReaction::where('note_id', $note->id)
                ->where('user_id', auth()->id())
                ->value('reaction')
            : null;
    
        // Получаем пользователей для каждой реакции с датами
        $reactionsUsers = [
            'like' => $note->reactions()->where('reaction', 'like')->with('user')->get(),
            'dislike' => $note->reactions()->where('reaction', 'dislike')->with('user')->get(),
            'heart' => $note->reactions()->where('reaction', 'heart')->with('user')->get(),
            'laugh' => $note->reactions()->where('reaction', 'laugh')->with('user')->get(),
            'wow' => $note->reactions()->where('reaction', 'wow')->with('user')->get(),
        ];

        return view('notes.show', compact('note', 'reactionsData', 'userReaction', 'reactionsUsers'));
    }

    public function edit(Note $note)
    {
        if ($note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещён!');
        }
    
        $tags = Tag::all();
        $files = $note->files; // Добавляем загрузку файлов
        return view('notes.edit', compact('note', 'tags', 'files'));
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещён!');
        }

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'is_public' => 'sometimes|boolean',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
            'files' => 'sometimes|array',
            'files.*' => 'file|max:153600', // 150MB для каждого файла
        ]);

        // Проверка доступного места перед обновлением
        if ($request->hasFile('files')) {
            $totalNewFilesSize = array_reduce($request->file('files'), function($carry, $file) {
                return $carry + ($file->isValid() ? $file->getSize() : 0);
            }, 0);

            if (!$this->checkUserStorage($totalNewFilesSize)) {
                return back()->withInput()->with('error', 
                    'Недостаточно места для загрузки файлов. Максимальный лимит: 150МБ. ' .
                    'Удалите ненужные файлы или освободите место.');
            }
        }

        // Обновляем заметку
        $note->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public'),
        ]);

        // Обновляем теги
        $note->tags()->sync($request->tags ?? []);

        // Обработка удаления файлов
        if ($request->has('deleted_files')) {
            $deletedFiles = json_decode($request->deleted_files, true) ?? [];
            
            // Удаляем файлы
            foreach ($deletedFiles as $fileId) {
                $file = NoteFile::find($fileId);
                if ($file && $file->note_id === $note->id) {
                    try {
                        Storage::disk('local')->delete($file->path);
                        $file->delete();
                    } catch (\Exception $e) {
                        \Log::error("File delete error: " . $e->getMessage());
                    }
                }
            }
            
            // Проверяем, остались ли ещё файлы у заметки
            if ($note->files()->count() === 0) {
                $noteFolder = "notes/{$note->id}";
                if (Storage::disk('local')->exists($noteFolder)) {
                    try {
                        Storage::disk('local')->deleteDirectory($noteFolder);
                    } catch (\Exception $e) {
                        \Log::error("Folder delete error: " . $e->getMessage());
                    }
                }
            }
        }

        // Обработка загрузки новых файлов
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME))
                                    .'.'.pathinfo($originalName, PATHINFO_EXTENSION);
                        $fileName = time().'_'.$safeName;
                        $storagePath = "notes/{$note->id}/{$fileName}";
                        
                        Storage::disk('local')->put($storagePath, file_get_contents($file->getRealPath()));
                        
                        $note->files()->create([
                            'name' => $originalName,
                            'path' => $storagePath,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("File upload error: " . $e->getMessage());
                        continue;
                    }
                }
            }
        }

        // Очистка неиспользуемых тегов
        $this->cleanupUnusedTags();

        return redirect()->route('notes.index')
            ->with('success', 'Заметка обновлена успешно.')
            ->with('storage_updated', true);
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Доступ запрещён!');
        }

        try {
            // Получаем путь к папке заметки
            $noteFolder = "notes/{$note->id}";
            
            // Проверяем существование папки и удаляем её рекурсивно
            if (Storage::disk('local')->exists($noteFolder)) {
                Storage::disk('local')->deleteDirectory($noteFolder);
            }
            
            // Удаляем саму заметку (каскадом удалятся и файлы из БД)
            $note->delete();
            
            return redirect()->route('notes.index')
                ->with('success', 'Заметка, все её файлы и папка удалены успешно.')
                ->with('storage_updated', true);
                
        } catch (\Exception $e) {
            \Log::error("Error deleting note {$note->id}: " . $e->getMessage());
            return back()->with('error', 'Произошла ошибка при удалении заметки и её файлов.');
        }
    }

    public function myFiles()
    {
        $user = auth()->user();
        
        // Получаем все файлы пользователя с пагинацией
        $files = NoteFile::whereHas('note', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['note' => function($query) {
                $query->select('id', 'title');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Получаем общий размер всех файлов пользователя
        $totalSize = $files->sum('size');
        $formattedTotalSize = $this->formatBytes($totalSize);
        
        // Получаем процент использования хранилища
        $storagePercentage = ($totalSize / self::MAX_USER_STORAGE) * 100;
        
        return view('files.my', compact('files', 'formattedTotalSize', 'storagePercentage'));
    }

    public function toggleReaction(Request $request, Note $note)
{
    $user = auth()->user();
    $reaction = $request->input('reaction');
    $remove = $request->input('remove', false);
    
    if ($remove) {
        NoteReaction::where('note_id', $note->id)
            ->where('user_id', $user->id)
            ->delete();
    } else {
        NoteReaction::updateOrCreate(
            ['note_id' => $note->id, 'user_id' => $user->id],
            ['reaction' => $reaction]
        );
    }
    
    $reactions = NoteReaction::where('note_id', $note->id)
        ->selectRaw('reaction, count(*) as count')
        ->groupBy('reaction')
        ->pluck('count', 'reaction')
        ->toArray();
    
    $reactionsUsers = [];
    $reactionTypes = ['like', 'dislike', 'heart', 'laugh', 'wow'];
    
    foreach ($reactionTypes as $type) {
        $reactionsUsers[$type] = NoteReaction::where('note_id', $note->id)
            ->where('reaction', $type)
            ->with('user')
            ->get()
            ->map(function($reaction) {
                return [
                    'user' => $reaction->user->name,
                    'created_at' => $reaction->created_at->toISOString(),
                ];
            });
    }
    
    return response()->json([
        'reactions' => $reactions,
        'reactionsUsers' => $reactionsUsers,
    ]);
}

public function getReactionUsers(Note $note, $reaction)
{
    $reactions = NoteReaction::where('note_id', $note->id)
        ->where('reaction', $reaction)
        ->with('user')
        ->get();
    
    return response()->json([
        'users' => $reactions->map(function($reaction) {
            return [
                'name' => $reaction->user->name,
                'created_at' => $reaction->created_at,
            ];
        })
    ]);
}

    // Вспомогательная функция для форматирования размера
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // В классе NoteController добавим новый метод
public function adminFiles(Request $request)
{
    if (!auth()->user()->isAdmin()) {
        abort(403, 'Доступ запрещён!');
    }

    // Получаем параметры фильтрации
    $search = $request->input('search');
    $userFilter = $request->input('user_id');
    $sizeFilter = $request->input('size');
    
    // Получаем всех пользователей для фильтра
    $users = User::orderBy('name')->get();

    // Запрос для файлов
    $query = NoteFile::with(['note', 'note.user'])
        ->when($search, function($q) use ($search) {
            $q->where('name', 'like', '%'.$search.'%')
              ->orWhereHas('note', function($q) use ($search) {
                  $q->where('title', 'like', '%'.$search.'%');
              });
        })
        ->when($userFilter, function($q) use ($userFilter) {
            $q->whereHas('note', function($q) use ($userFilter) {
                $q->where('user_id', $userFilter);
            });
        })
        ->when($sizeFilter, function($q) use ($sizeFilter) {
            if ($sizeFilter === 'large') {
                $q->where('size', '>', 10485760); // >10MB
            } elseif ($sizeFilter === 'small') {
                $q->where('size', '<', 1048576); // <1MB
            }
        });

    // Сортировка
    $sort = $request->input('sort', 'newest');
    if ($sort === 'newest') {
        $query->orderBy('created_at', 'desc');
    } elseif ($sort === 'oldest') {
        $query->orderBy('created_at', 'asc');
    } elseif ($sort === 'largest') {
        $query->orderBy('size', 'desc');
    } elseif ($sort === 'smallest') {
        $query->orderBy('size', 'asc');
    }

    $files = $query->paginate(20);
    
    // Общий размер всех файлов (для статистики)
    $totalSize = NoteFile::sum('size');
    $formattedTotalSize = $this->formatBytes($totalSize);
    
    // Количество пользователей с файлами
    $usersWithFiles = User::has('notes.files')->count();

    return view('admin.files', compact(
        'files', 
        'users',
        'search',
        'userFilter',
        'sizeFilter',
        'sort',
        'totalSize',
        'formattedTotalSize',
        'usersWithFiles'
    ));
}
}
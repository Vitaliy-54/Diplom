<?php

namespace App\Http\Controllers;

use App\Models\Literature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LiteratureController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        
        $query = Literature::query();
        
        if ($category) {
            $query->where('category', $category);
        }
        
        $literatures = $query->latest()->get();
        
        // Список уникальных категорий для меню
        $categories = Literature::select('category')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');
        
        return view('literature.index', compact('literatures', 'categories', 'category'));
    }

    public function create()
    {
        return view('literature.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100', // Ограничение 100 символов
            'description' => 'nullable|string|max:255', // Ограничение 255 символов
            'category' => 'nullable|string|max:30', // Ограничение 30 символов
            'file' => 'required|file|mimes:pdf,doc,docx,txt,rtf,epub,mobi|max:10240', // 10MB max
        ], [
            'title.max' => 'Название не может быть длиннее 100 символов',
            'description.max' => 'Описание не может быть длиннее 255 символов',
            'category.max' => 'Категория не может быть длиннее 30 символов',
            'file.required' => 'Необходимо выбрать файл',
            'file.mimes' => 'Файл должен быть формата: pdf, doc, docx, txt, rtf, epub, mobi',
            'file.max' => 'Размер файла не может превышать 10MB',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('literatures', $fileName, 'public');

        Literature::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('literature.index')
            ->with('success', 'Материал успешно добавлен!');
    }

    public function edit(Literature $literature)
    {
        // Проверяем, что пользователь - автор или админ
        if (auth()->user()->role !== 'admin' && $literature->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('literature.edit', compact('literature'));
    }

    public function update(Request $request, Literature $literature)
    {
        // Проверяем, что пользователь - автор или админ
        if (auth()->user()->role !== 'admin' && $literature->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:100', // Ограничение 100 символов
            'description' => 'nullable|string|max:255', // Ограничение 255 символов
            'category' => 'nullable|string|max:30', // Ограничение 30 символов
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,rtf,epub,mobi|max:10240',
        ], [
            'title.max' => 'Название не может быть длиннее 100 символов',
            'description.max' => 'Описание не может быть длиннее 255 символов',
            'category.max' => 'Категория не может быть длиннее 30 символов',
            'file.mimes' => 'Файл должен быть формата: pdf, doc, docx, txt, rtf, epub, mobi',
            'file.max' => 'Размер файла не может превышать 10MB',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ];

        // Если загружен новый файл
        if ($request->hasFile('file')) {
            // Удаляем старый файл
            Storage::disk('public')->delete($literature->file_path);
            
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('literatures', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['file_type'] = $file->getMimeType();
        }

        $literature->update($data);

        return redirect()->route('literature.index')
            ->with('success', 'Материал успешно обновлен!');
    }

    public function destroy(Literature $literature)
    {
        // Проверяем, что пользователь - автор или админ
        if (auth()->user()->role !== 'admin' && $literature->user_id !== auth()->id()) {
            abort(403);
        }

        // Удаляем файл
        Storage::disk('public')->delete($literature->file_path);
        
        // Удаляем запись
        $literature->delete();

        return redirect()->route('literature.index')
            ->with('success', 'Материал успешно удален!');
    }

    public function download(Literature $literature)
    {
        if (!Storage::disk('public')->exists($literature->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($literature->file_path, $literature->file_name);
    }
}
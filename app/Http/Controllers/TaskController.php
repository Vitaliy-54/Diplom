<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Проверка прав доступа
    private function checkPermissions(Task $task)
    {
        // Проверяем, что текущий пользователь является автором задачи или администратором
        if ($task->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'У вас нет прав на выполнение этого действия.');
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $tasks = $user->tasks();
    
        // Фильтрация по статусу
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'active':
                    $tasks->where('completed', false);
                    break;
                case 'completed':
                    $tasks->where('completed', true);
                    break;
            }
        }
    
        // Фильтрация по категории
        if ($request->has('category')) {
            $tasks->where('category', $request->category);
        }
    
        // Сортировка
        $tasks = $tasks->orderBy('created_at', 'desc')->get();
    
        // Для AJAX запросов - возвращаем только частичное представление
        if ($request->ajax()) {
            return view('tasks.partials.task-list', ['tasks' => $tasks]);
        }
    
        // Для обычных запросов - полная страница
        $categories = $user->tasks()->pluck('category')->unique()->filter();
        return view('tasks.index', [
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }
    
    // Просмотр задачи
    public function show(Task $task)
    {
        // Проверка прав доступа
        $this->checkPermissions($task);

        return view('tasks.show', ['task' => $task]);
    }

    // Переключение статуса задачи
    public function toggleCompletion(Request $request, Task $task)
    {
        // Проверка прав доступа
        $this->checkPermissions($task);

        // Изменяем статус задачи
        $task->completed = !$task->completed;

        // Если задача помечена как выполненная, обновляем дату выполнения
        if ($task->completed) {
            $task->due_date = now()->toDateString(); // Используем текущую дату в формате YYYY-MM-DD
        } else {
            $task->due_date = null; // Сбрасываем дату выполнения, если задача не выполнена
        }

        $task->save();

        // Форматируем дату в формат ДД.ММ.ГГГГ
        $formattedDueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('m.d.Y') : null;

        // Возвращаем обновленный статус задачи и отформатированную дату
        return response()->json([
            'completed' => $task->completed,
            'due_date' => $formattedDueDate,
        ]);
    }

    // Форма создания задачи
    public function create()
    {
        return view('tasks.create');
    }

    // Сохранение новой задачи
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'new_category' => 'nullable|string|max:255', // Добавляем валидацию для новой категории
        ]);

        // Если выбрана опция "Новая категория...", используем значение из поля new_category
        $category = $request->category === 'new_category_task' ? $request->new_category : $request->category;

        $task = Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'category' => $category, // Используем выбранную или новую категорию
            'completed' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Задача успешно создана.');
    }

    // Форма редактирования задачи
    public function edit(Task $task)
    {
        // Проверка прав доступа
        $this->checkPermissions($task);

        // Получаем список уникальных категорий для текущего пользователя
        $categories = auth()->user()->tasks()->pluck('category')->unique()->filter();

        return view('tasks.edit', [
            'task' => $task,
            'categories' => $categories,
        ]);
    }

    // Обновление задачи
    public function update(Request $request, Task $task)
    {
        // Проверка прав доступа
        $this->checkPermissions($task);

        // Валидация данных
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'new_category' => 'nullable|string|max:255', // Валидация для новой категории
        ]);

        // Если выбрана опция "Новая категория...", используем значение из поля new_category
        $category = $request->category === 'new_category_task' ? $request->new_category : $request->category;

        // Обновляем только название, описание и категорию
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $category, // Используем выбранную или новую категорию
        ]);

        return redirect()->route('tasks.index')->with('success', 'Задача успешно обновлена.');
    }

    // Удаление задачи
    public function destroy(Task $task)
    {
        // Проверка прав доступа
        $this->checkPermissions($task);

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Задача успешно удалена.');
    }
}
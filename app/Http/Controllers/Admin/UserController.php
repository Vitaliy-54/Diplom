<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\CustomNotification; // Импортируем класс уведомления
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\NoteFile;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Показать список всех пользователей.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Получаем всех пользователей с последней сессией, отсортированной по last_activity
    $users = User::with('lastSession')->get();
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function getStatus()
    {
        $currentUserId = auth()->id(); // ID текущего пользователя
    
        $users = User::with(['lastSession', 'logs'])->get()->map(function ($user) use ($currentUserId) {
            // Для текущего пользователя всегда возвращаем "Онлайн"
            $isOnline = ($user->id === $currentUserId) || ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp);
            $lastActivity = $user->logs->first() ? Carbon::parse($user->logs->first()->last_activity_at) : null;
            $lastLogout = $user->logs->first() ? Carbon::parse($user->logs->first()->last_logout_at) : null;
    
            return [
                'id' => $user->id,
                'is_online' => $isOnline,
                'last_activity_at' => $lastActivity ? $lastActivity->format('d.m.Y H:i') : null,
                'last_logout_at' => $lastLogout ? $lastLogout->format('d.m.Y H:i') : null,
            ];
        });
    
        return response()->json(['users' => $users]);
    }

    // Метод для форматирования размера файлов
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Показать форму создания нового пользователя.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Сохранить нового пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'], // Валидация роли
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Сохраняем роль
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно создан.');
    }

    /**
     * Удалить пользователя.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Вы не можете удалить свою учетную запись.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно удален.');
    }

    /**
     * Показать форму для изменения пароля пользователя.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function editPassword(User $user)
    {
        return view('admin.users.edit-password', compact('user'));
    }

    /**
     * Обновить пароль пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пароль пользователя успешно изменен.');
    }

    /**
     * Изменить роль пользователя.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeRole(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Вы не можете изменить роль своей учетной записи.');
        }

        $newRole = $user->role === 'admin' ? 'user' : 'admin';
        $user->update(['role' => $newRole]);

        $action = $newRole === 'admin' ? 'повышена' : 'понижена';
        return redirect()->route('admin.users.index')->with('success', "Роль пользователя успешно {$action}.");
    }

    /**
     * Показать форму для отправки уведомления.
     *
     * @return \Illuminate\View\View
     */
    public function showSendNotificationForm()
    {
        $users = User::all(); // Получаем всех пользователей
        return view('admin.users.send-notification', compact('users'));
    }

    public function sendNotification(Request $request)
    {
        // Валидация данных
        $request->validate([
            'send_to_all' => ['required', 'boolean'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'],
            'message' => ['required', 'string'],
        ]);

        // Если не выбрано "Отправить всем" и не выбраны пользователи
        if ($request->send_to_all == 0 && empty($request->user_ids)) {
            return redirect()->back()->withErrors(['user_ids' => 'Выберите хотя бы одного пользователя.']);
        }

        // Если выбрано "Отправить всем"
        if ($request->send_to_all == 1) {
            $users = User::all();
        } else {
            // Иначе получаем выбранных пользователей
            $users = User::whereIn('id', $request->user_ids)->get();
        }

        // Отправляем уведомление каждому пользователю
        foreach ($users as $user) {
            // Создаем уведомление
            $notification = new CustomNotification($request->message);

            // Сохраняем уведомление в таблицу notifications
            $user->notify($notification);
        }

        return redirect()->route('admin.users.sent-notifications')->with('success', 'Уведомление успешно отправлено.');
    }

    /**
     * Показать отправленные уведомления.
     *
     * @return \Illuminate\View\View
     */
    public function sentNotifications()
    {
        // Получаем все уведомления из таблицы notifications
        $notifications = DB::table('notifications')
            ->join('users', 'notifications.notifiable_id', '=', 'users.id')
            ->where('notifications.notifiable_type', User::class) // Убедимся, что тип - User
            ->select(
                'notifications.id as notification_id',
                'notifications.data',
                'notifications.read_at',
                'notifications.created_at',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('notifications.created_at', 'desc')
            ->get();
    
        // Группируем уведомления по содержимому (data)
        $groupedNotifications = $notifications->groupBy(function ($item) {
            return md5($item->data); // Используем хэш содержимого для группировки
        });
    
        // Преобразуем данные для удобного отображения
        $notifications = $groupedNotifications->map(function ($group) {
            $firstNotification = $group->first();
    
            return [
                'notification_ids' => $group->pluck('notification_id')->toArray(), // ID всех уведомлений
                'data' => json_decode($firstNotification->data, true),
                'created_at' => Carbon::parse($firstNotification->created_at), // Преобразуем в Carbon
                'users' => $group->map(function ($item) {
                    return [
                        'user_id' => $item->user_id,
                        'user_name' => $item->user_name,
                        'user_email' => $item->user_email,
                        'read_at' => $item->read_at ? Carbon::parse($item->read_at) : null, // Статус прочтения
                        'notification_id' => $item->notification_id, // Добавляем notification_id для каждого пользователя
                    ];
                }),
            ];
        });
    
        return view('admin.users.sent-notifications', compact('notifications'));
    }

    /**
     * Показать форму для изменения электронной почты пользователя.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function editEmail(User $user)
    {
        return view('admin.users.edit-email', compact('user'));
    }

    /**
     * Обновить электронную почту пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmail(Request $request, User $user)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->email = $request->email;

        if ($request->has('require_verification')) {
            $user->email_verified_at = null; // сбрасываем подтверждение
            $user->save();
        } else {
            $user->email_verified_at = now(); // считаем подтверждённым
            $user->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'Электронная почта успешно обновлена.');
    }

    public function info(User $user)
    {
        $avatarDir = "avatars/{$user->id}";
        $avatarFile = collect(Storage::files($avatarDir))
            ->first(fn($f) => preg_match('/^avatars\/' . $user->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));

        $lastActivity = optional($user->logs->first())->last_activity_at;
        $isOnline = $user->id === auth()->id() ||
            ($user->lastSession && $user->lastSession->last_activity > now()->subMinutes(5)->timestamp);

        return view('users.info', compact('user', 'avatarFile', 'lastActivity', 'isOnline'));
    }
}

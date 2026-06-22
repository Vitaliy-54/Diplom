<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Показать страницу с уведомлениями.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Получаем все уведомления для текущего пользователя
        $notifications = auth()->user()->notifications;

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Показать форму для редактирования уведомления.
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\View\View
     */
    public function edit(DatabaseNotification $notification)
    {
        // Проверяем, что уведомление принадлежит текущему пользователю или пользователь — админ
        if ($notification->notifiable_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('admin.users.sent-notifications')->with('error', 'У вас нет прав для редактирования этого уведомления.');
        }

        return view('admin.notifications.edit', compact('notification'));
    }

    /**
     * Обновить уведомление.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return redirect()->route('admin.users.sent-notifications')->with('error', 'У вас нет прав для редактирования этого уведомления.');
        }

        $request->validate([
            'message' => ['required', 'string'],
        ]);

        // Получаем хэш данных текущего уведомления для поиска аналогичных
        $currentDataHash = md5(json_encode($notification->data));

        // Обновляем данные для всех уведомлений с таким же содержимым
        DB::table('notifications')
            ->whereRaw('md5(data) = ?', [$currentDataHash])
            ->update([
                'data' => json_encode(array_merge($notification->data, [
                    'message' => $request->message
                ]))
            ]);

        return redirect()->route('admin.users.sent-notifications')->with('success', 'Уведомление успешно обновлено для всех пользователей.');
    }

    /**
     * Удалить уведомление для конкретного пользователя.
     *
     * @param  string  $notificationId
     * @param  string  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroySentForUser($notificationId, $userId)
    {
        // Проверяем, является ли текущий пользователь админом или владельцем уведомления
        if (auth()->user()->role !== 'admin' && auth()->id() != $userId) {
            return redirect()->route('admin.users.sent-notifications')->with('error', 'У вас нет прав для удаления этого уведомления.');
        }

        // Удаляем уведомление для конкретного пользователя
        DB::table('notifications')
            ->where('id', $notificationId)
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', User::class)
            ->delete();

        return redirect()->route('admin.users.sent-notifications')->with('success', 'Уведомление успешно удалено для пользователя.');
    }

    /**
     * Удалить уведомление для всех пользователей (админ) или для текущего пользователя.
     *
     * @param  string  $groupId  // Хэш содержимого сообщения (группа уведомлений)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroySent($groupId)
    {
        // Если текущий пользователь — админ, удаляем все уведомления в группе
        if (auth()->user()->role === 'admin') {
            DB::table('notifications')
                ->whereRaw('md5(data) = ?', [$groupId]) // Удаляем по хэшу содержимого
                ->delete();

            return redirect()->route('admin.users.sent-notifications')->with('success', 'Уведомление успешно удалено для всех пользователей.');
        }

        // Если текущий пользователь не админ, удаляем уведомление только для себя
        DB::table('notifications')
            ->whereRaw('md5(data) = ?', [$groupId]) // Удаляем по хэшу содержимого
            ->where('notifiable_id', auth()->id())  // Только для текущего пользователя
            ->where('notifiable_type', User::class)
            ->delete();

        return redirect()->route('admin.users.sent-notifications')->with('success', 'Уведомление успешно удалено.');
    }

    /**
     * Удалить уведомление.
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DatabaseNotification $notification)
    {
        // Проверяем, что уведомление принадлежит текущему пользователю
        if ($notification->notifiable_id === auth()->id()) {
            $notification->delete();
            return redirect()->route('admin.notifications.index')->with('success', 'Уведомление успешно удалено.');
        }

        return redirect()->route('admin.notifications.index')->with('error', 'Нельзя удалить это уведомление.');
    }

    /**
     * Пометить уведомление как прочитанное.
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        // Проверяем, что уведомление принадлежит текущему пользователю
        if ($notification->notifiable_id === auth()->id()) {
            $notification->markAsRead();
            return redirect()->route('admin.notifications.index')->with('success', 'Уведомление помечено как прочитанное.');
        }

        return redirect()->route('admin.notifications.index')->with('error', 'Нельзя пометить это уведомление как прочитанное.');
    }
}

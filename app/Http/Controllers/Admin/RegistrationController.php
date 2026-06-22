<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSetting; // Импортируем модель для управления регистрацией
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Отображение страницы управления регистрацией.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Получаем текущий статус регистрации
        $registrationSetting = RegistrationSetting::first();

        // Передаем данные в представление
        return view('admin.registration-control', [
            'registrationSetting' => $registrationSetting,
        ]);
    }

    /**
 * Переключение статуса регистрации.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function toggleRegistration(Request $request)
{
    // Валидация данных
    $request->validate([
        'registration_enabled' => ['required', 'boolean'],
    ]);

    // Обновляем статус регистрации
    $registrationSetting = RegistrationSetting::first();
    $registrationSetting->update([
        'registration_enabled' => $request->registration_enabled,
    ]);

    // Перенаправляем обратно с сообщением об успехе
    return redirect()->route('admin.registration-control')
        ->with('success', 'Статус регистрации успешно обновлен.');
}
}
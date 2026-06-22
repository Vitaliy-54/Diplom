<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class VerificationController extends Controller
{
    /**
     * Show the verification code form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.verify-email');
    }

    /**
     * Verify the user's email with the code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('message', 'Ваш email уже был подтвержден.');
        }

        $cachedData = Cache::get('email_verification:'.$user->email);

        if (!$cachedData || $cachedData['code'] != $request->code) {
            return back()->withErrors(['code' => 'Неверный код подтверждения.']);
        }

        if ($cachedData['user_id'] != $user->id) {
            return back()->withErrors(['code' => 'Неверный код подтверждения.']);
        }

        $user->markEmailAsVerified();
        Cache::forget('email_verification:'.$user->email); // Удаляем код из кэша

        event(new Verified($user));

        return redirect('/dashboard')->with('message', 'Ваш email успешно подтвержден.');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Новый код подтверждения был отправлен на ваш email!');
    }
}
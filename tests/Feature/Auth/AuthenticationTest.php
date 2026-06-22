<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Fortify\Features;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Тест: пользователь должен успешно аутентифицироваться
     */
    public function test_login_should_authenticate_user_when_credentials_are_correct()
    {
        $password = 'password123';
        
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'role' => 'user'
        ]);

        $response = Livewire::test('pages.auth.login')
            ->set('form.email', 'user@example.com')
            ->set('form.password', $password)
            ->call('login');
        
        $redirect = $response->effects['redirect'] ?? null;
        $this->assertNotNull($redirect);
        $this->assertStringContainsString('/dashboard', $redirect);
        $this->assertAuthenticated();
    }

    /**
     * Тест: неверный пароль должен возвращать ошибку
     */
    public function test_login_should_return_error_when_password_is_incorrect()
    {
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('correct_password'),
            'email_verified_at' => now(),
            'role' => 'user'
        ]);

        Livewire::test('pages.auth.login')
            ->set('form.email', 'user@example.com')
            ->set('form.password', 'wrong_password')
            ->call('login')
            ->assertHasErrors(['form.email']);

        $this->assertGuest();
    }


    /**
     * Тест: неподтверждённый email
     */
    public function test_login_with_unverified_email()
    {
        $user = User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
            'role' => 'user'
        ]);

        $response = Livewire::test('pages.auth.login')
            ->set('form.email', 'unverified@example.com')
            ->set('form.password', 'password123')
            ->call('login');
        
        $redirect = $response->effects['redirect'] ?? null;
        
        $hasVerifiedMiddleware = $this->app->router->hasMiddlewareGroup('verified');
        
        if ($hasVerifiedMiddleware && $redirect) {
            $this->assertStringContainsString('/email/verify', $redirect);
            $this->assertGuest();
        } else {
            // Приложение не требует верификации email
            $this->assertStringContainsString('/dashboard', $redirect);
            $this->assertAuthenticated();
        }
    }
}
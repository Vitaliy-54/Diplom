<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Policies\CommentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрация политики для комментариев
        Gate::policy(Comment::class, CommentPolicy::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // Получаем IP-адреса Cloudflare
            $response = Http::get('https://www.cloudflare.com/ips-v4');
            $cloudflareIps = explode("\n", $response->body());

            Request::setTrustedProxies(
                $cloudflareIps,
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
            );
        }
    }
}
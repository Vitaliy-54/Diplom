<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationShareLinksTable extends Migration
{
    public function up()
    {
        Schema::create('calculation_share_links', function (Blueprint $table) {
            $table->id();
            
            // Связь с вычислением (полиморфная, так как есть ND3+ и HO3+)
            $table->morphs('calculable');
            
            // Пользователь, который создал ссылку
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Уникальный токен для публичного доступа
            $table->string('token', 64)->unique()->index();
            
            // Настройки ссылки
            $table->string('title')->nullable(); // Пользовательское название ссылки
            $table->text('description')->nullable(); // Описание
            $table->boolean('is_active')->default(true); // Активна ли ссылка
            
            // Даты действия
            $table->timestamp('expires_at')->nullable(); // Дата истечения (null = бессрочно)
            $table->timestamp('last_accessed_at')->nullable(); // Последний доступ
            
            // Статистика
            $table->integer('views')->default(0); // Количество просмотров
            $table->integer('unique_views')->default(0); // Уникальные просмотры (по IP или user_id)
            $table->json('access_log')->nullable(); // Лог доступа (максимум последние 100 записей)
            
            // Парольная защита (опционально)
            $table->string('password_hash')->nullable(); // Хеш пароля, если установлен
            
            // Настройки доступа
            $table->boolean('allow_comments')->default(true); // Разрешить комментарии
            $table->boolean('allow_download')->default(true); // Разрешить скачивание
            $table->boolean('allow_copy_to_account')->default(true); // Разрешить копирование в аккаунт
            
            $table->timestamps();
            $table->softDeletes(); // Мягкое удаление
            
            // Индексы для оптимизации (короткие имена)
            $table->index(['calculable_type', 'calculable_id', 'is_active'], 'calc_share_active_idx');
            $table->index(['token', 'is_active', 'expires_at'], 'calc_share_token_active_expires_idx');
            $table->index('created_by', 'calc_share_created_by_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calculation_share_links');
    }
}
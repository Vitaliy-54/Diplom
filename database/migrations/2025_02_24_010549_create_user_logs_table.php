<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID пользователя
            $table->timestamp('last_activity_at')->nullable(); // Время последней активности
            $table->timestamp('last_logout_at')->nullable(); // Время выхода
            $table->timestamps();
    
            // Внешний ключ для связи с таблицей users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
};

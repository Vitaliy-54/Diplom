<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Связь с пользователем
            $table->string('title'); // Название задачи
            $table->text('description')->nullable(); // Описание задачи
            $table->boolean('completed')->default(false); // Статус выполнения
            $table->date('due_date')->nullable(); // Дата выполнения
            $table->string('category')->nullable(); // Категория задачи
            $table->timestamps(); // created_at и updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
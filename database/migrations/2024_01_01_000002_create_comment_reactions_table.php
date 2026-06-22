<?php
// database/migrations/2024_01_01_000002_create_comment_reactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('reaction', ['like', 'dislike', 'heart', 'laugh', 'wow']);
            $table->timestamps();
            
            $table->unique(['comment_id', 'user_id']);
            $table->index(['comment_id', 'reaction']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_reactions');
    }
};
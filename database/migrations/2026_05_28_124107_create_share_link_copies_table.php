<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareLinkCopiesTable extends Migration
{
    public function up()
    {
        Schema::create('share_link_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_link_id')->constrained('calculation_share_links')->onDelete('cascade');
            $table->foreignId('copied_by')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('new_calculation_id');
            $table->string('new_calculation_type');
            $table->timestamps();
            
            $table->index(['share_link_id', 'copied_by']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_link_copies');
    }
}
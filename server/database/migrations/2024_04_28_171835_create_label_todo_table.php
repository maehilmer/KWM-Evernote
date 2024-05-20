<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('label_todo', function (Blueprint $table) {
            $table->foreignId('label_id')->constrained()->onDelete('cascade');
            $table->foreignId('todo_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->primary(['label_id', 'todo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_label');
    }
};

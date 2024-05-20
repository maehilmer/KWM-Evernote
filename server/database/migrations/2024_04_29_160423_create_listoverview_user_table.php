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
        Schema::create('listoverview_user', function (Blueprint $table) {
            $table->foreignId('listoverview_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('permission'); // ist User berechtigt, Liste zu sehen
            $table->timestamps();
            $table->primary(['listoverview_id', 'user_id']); //zusammengesetzter PK
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listoverview_user');
    }
};

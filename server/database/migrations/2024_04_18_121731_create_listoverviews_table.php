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
        Schema::create('listoverviews', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Annahme: der Titel ist nicht einzigartig, sondern kann bei mehrern Notizen genau gleich sein
            $table->boolean('isPublic')->default(true); // Annahme: Listen sind an sich öffentlich
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listoverviews');
    }
};

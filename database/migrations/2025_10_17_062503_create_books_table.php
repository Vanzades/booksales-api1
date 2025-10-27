<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('cover_photo', 255)->nullable(); // path cover
            $table->foreignId('genre_id')->constrained('genres')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('author_id')->constrained('authors')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();

            $table->index(['genre_id', 'author_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('books');
    }
};

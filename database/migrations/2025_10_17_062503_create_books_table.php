<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('isbn')->unique();
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->year('published_year')->nullable();
            $table->timestamps();

            $table->index(['author_id', 'title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

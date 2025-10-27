<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 25)->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();

            $table->index(['customer_id', 'book_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};

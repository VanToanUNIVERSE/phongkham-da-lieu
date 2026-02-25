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
        Schema::create('medicine_transactions', function (Blueprint $table) { 
            $table->id();

            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['import', 'export']); // nhập hay xuất
            $table->integer('quantity');

            $table->string('note')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_transactions');
    }
};

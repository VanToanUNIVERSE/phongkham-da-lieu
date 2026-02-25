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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();

            $table->string('name');                 // tên thuốc
            $table->string('unit')->default('viên'); // đơn vị: viên, chai...
            $table->integer('stock')->default(0);    // số lượng tồn

            $table->decimal('price', 10, 2)->nullable(); // giá bán
            $table->date('expiry_date')->nullable();     // hạn dùng

            $table->text('description')->nullable();     // mô tả
            $table->boolean('is_active')->default(true); // còn sử dụng không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};

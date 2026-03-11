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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            
            // Xóa theo bệnh nhân
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            
            // Có thể liên kết đơn khám / bệnh án nếu cần
            $table->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            
            // Doanh Thu (Giá VNĐ, max 999 triệu = 11 số)
            $table->decimal('total_amount', 12, 0)->default(0); 
            
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // cash, transfer, card
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

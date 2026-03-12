<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();          // CTR-2026-001
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('admins');

            // المبالغ
            $table->decimal('total_amount', 10, 2);               // المبلغ الإجمالي للعقد
            $table->decimal('discount',     10, 2)->default(0);   // خصم على العقد كله
            $table->decimal('tax',          10, 2)->default(0);   // ضريبة إن وجدت
            $table->decimal('net_amount',   10, 2);               // الصافي بعد الخصم والضريبة

            // التواريخ
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // الحالة
            $table->enum('status', [
                'draft',      // مسودة
                'active',     // نشط
                'completed',  // منتهي ومدفوع بالكامل
                'cancelled',  // ملغي
            ])->default('draft');

            $table->text('scope')->nullable();        // وصف نطاق العمل
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};

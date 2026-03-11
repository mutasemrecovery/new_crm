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
       Schema::create('contract_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();

            $table->integer('payment_number');                    // رقم الدفعة (1، 2، 3...)
            $table->string('label')->nullable();                  // مثال: "دفعة أولى - عند التوقيع"
            $table->decimal('amount', 10, 2);                     // قيمة هذه الدفعة

            $table->date('due_date');                             // تاريخ الاستحقاق
            $table->date('paid_at')->nullable();                  // تاريخ الدفع الفعلي

            $table->enum('status', [
                'pending',    // لم تُدفع بعد
                'paid',       // مدفوعة
                'overdue',    // متأخرة
                'cancelled',  // ملغاة
            ])->default('pending');

            $table->string('payment_method')->nullable();         // كاش / بنك / أونلاين / شيك
            $table->string('reference')->nullable();              // رقم الحوالة أو الشيك
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
        Schema::dropIfExists('contract_payments');
    }
};

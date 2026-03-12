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
         Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();           // RCP-2026-001
            $table->foreignId('contract_payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('issued_by')->constrained('admins');

            $table->decimal('amount', 10, 2);                     // المبلغ المقبوض
            $table->date('receipt_date');
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();              // رقم حوالة / شيك
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
        Schema::dropIfExists('payment_receipts');
    }
};

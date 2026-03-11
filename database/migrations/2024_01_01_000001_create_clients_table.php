<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // اسم العميل / الشركة
            $table->string('contact_person')->nullable();    // اسم الشخص المسؤول
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('industry')->nullable();          // قطاع العميل
            $table->text('notes')->nullable();               // ملاحظات
            $table->enum('status', ['active', 'pending', 'paused', 'closed'])->default('active');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->date('contract_start')->nullable();      // بداية التعاقد
            $table->date('contract_end')->nullable();
            $table->decimal('monthly_value', 10, 2)->default(0); // قيمة العقد الشهرية
            $table->foreignId('assigned_sales_id')->nullable()->constrained('users')->nullOnDelete(); // موظف المبيعات المسؤول
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

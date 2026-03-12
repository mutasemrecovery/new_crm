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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->tinyInteger('month');                      // 1-12
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('commissions_amount', 10, 2)->default(0);
            $table->decimal('bonuses', 10, 2)->default(0);     // مكافآت يدوية
            $table->decimal('deduction_absence', 10, 2)->default(0);  // خصم غياب
            $table->decimal('deduction_late', 10, 2)->default(0);     // خصم تأخر
            $table->decimal('deduction_manual', 10, 2)->default(0);   // خصم يدوي
            $table->text('deduction_manual_note')->nullable();
            $table->decimal('net_salary', 10, 2);              // الصافي
            $table->integer('working_days')->default(0);       // أيام العمل الفعلية
            $table->integer('absent_days')->default(0);
            $table->integer('late_count')->default(0);
            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->date('paid_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->text('notes')->nullable();
           $table->decimal('overtime_amount', 10, 2)->default(0);
           $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->timestamps();
            $table->unique(['employee_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
};

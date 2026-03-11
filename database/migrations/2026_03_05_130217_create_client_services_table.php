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
        // الخدمات التي يأخذها كل عميل (pivot مع تفاصيل)
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2)->default(0);    // السعر الشهري لهذه الخدمة
            $table->text('details')->nullable();             // تفاصيل الخدمة لهذا العميل
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->date('start_date')->nullable();
            $table->unsignedInteger('monthly_quantity')->default(1);            // الكمية الشهرية (للخدمات المتكررة) أو كمية المشروع (دائماً 1 للمشاريع)
            // هل يتم توزيع التاسكات أسبوعياً؟ (للخدمات المتكررة فقط)
            // مثال: 12 صورة / شهر → 3 أسبوعياً
            $table->boolean('distribute_weekly')->default(true);
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
        Schema::dropIfExists('client_services');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // حساب الدخول
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('job_title');                     // المسمى الوظيفي
            $table->enum('department', [
                'design',           // تصميم
                'video',            // فيديو
                'development',      // برمجة
                'social_media',     // سوشيال ميديا
                'marketing',        // تسويق
                'sales',            // مبيعات
                'accounting',       // محاسبة
                'management',       // إدارة
            ])->default('design');
            $table->json('specializations')->nullable();     // مهاراته مثل: ["Photoshop","Laravel","Premiere"]
            $table->decimal('salary', 10, 2)->default(0);   // الراتب
            $table->boolean('is_sales')->default(false);     // هل هو موظف مبيعات؟
            $table->decimal('commission_rate', 5, 2)->default(0); // نسبة العمولة % (للمبيعات)
            $table->enum('commission_type', ['per_deal', 'monthly_percentage'])->default('per_deal');
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'vacation'])->default('active');
            $table->date('hire_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

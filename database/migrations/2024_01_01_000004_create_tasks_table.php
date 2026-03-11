<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users'); // المدير اللي أنشأ التاسك
            $table->enum('status', [
                'todo',         // لم يبدأ
                'in_progress',  // جاري
                'review',       // مراجعة
                'done',         // مكتمل
                'cancelled',    // ملغي
            ])->default('todo');
            $table->enum('priority', ['urgent', 'high', 'medium', 'low'])->default('medium');
            $table->date('due_date')->nullable();
            $table->integer('progress')->default(0);         // 0-100
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // تعيين الموظفين على التاسك (ممكن أكثر من موظف)
        Schema::create('task_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();
        });

        // تعليقات التاسك
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('attachment')->nullable();        // مرفقات
            $table->timestamps();
        });

        // لايكات التعليقات
        Schema::create('task_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_comment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['task_comment_id', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_comment_likes');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('task_employees');
        Schema::dropIfExists('tasks');
    }
};

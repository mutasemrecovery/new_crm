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
         Schema::create('task_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();   // الحالة السابقة
            $table->string('to_status');                 // الحالة الجديدة
            $table->unsignedBigInteger('changed_by');    // user_id أو admin_id
            $table->string('changer_type')->default('user'); // 'user' | 'admin'
            $table->string('changer_name')->nullable();  // نحفظ الاسم مباشرة لأن المصدر مختلف
            $table->text('note')->nullable();
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
        Schema::dropIfExists('task_status_logs');
    }
};

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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->decimal('check_in_lat',  10, 7)->nullable();
            $table->decimal('check_in_lng',  10, 7)->nullable();
            $table->decimal('check_out_lat', 10, 7)->nullable();
            $table->decimal('check_out_lng', 10, 7)->nullable();
            $table->decimal('check_in_distance',  6, 1)->nullable(); // بالمتر
            $table->decimal('check_out_distance', 6, 1)->nullable();
            $table->enum('status', ['present','late','half_day','absent'])->default('present');
            $table->text('notes')->nullable();
            $table->decimal('overtime_minutes', 8, 1)->default(0);
            $table->integer('late_minutes')->default(0);
            $table->timestamps();
 
            $table->unique(['employee_id', 'date']); // سجل واحد في اليوم
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};

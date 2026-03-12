<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
         Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('day_of_week');   // 0=Sunday … 6=Saturday
            $table->boolean('is_working_day')->default(true);
            $table->time('start_time')->nullable(); // وقت بداية الدوام
            $table->time('end_time')->nullable();   // وقت نهاية الدوام
            $table->integer('grace_minutes')->default(0); // دقائق سماح قبل اعتبار التأخير
            $table->timestamps();
 
            $table->unique('day_of_week');
        });

         // ── seed بيانات افتراضية (السبت - الأربعاء 10-6 ، الخميس 10-2 ، الجمعة إجازة) ──
        $defaults = [
            // day_of_week: 0=Sun,1=Mon,2=Tue,3=Wed,4=Thu,5=Fri,6=Sat
            ['day_of_week' => 0, 'is_working_day' => true,  'start_time' => '10:00', 'end_time' => '18:00', 'grace_minutes' => 15],
            ['day_of_week' => 1, 'is_working_day' => true,  'start_time' => '10:00', 'end_time' => '18:00', 'grace_minutes' => 15],
            ['day_of_week' => 2, 'is_working_day' => true,  'start_time' => '10:00', 'end_time' => '18:00', 'grace_minutes' => 15],
            ['day_of_week' => 3, 'is_working_day' => true,  'start_time' => '10:00', 'end_time' => '18:00', 'grace_minutes' => 15],
            ['day_of_week' => 4, 'is_working_day' => true,  'start_time' => '10:00', 'end_time' => '14:00', 'grace_minutes' => 15],
            ['day_of_week' => 5, 'is_working_day' => false, 'start_time' => null,    'end_time' => null,    'grace_minutes' => 0],
            ['day_of_week' => 6, 'is_working_day' => true,  'start_time' => '10:00', 'end_time' => '18:00', 'grace_minutes' => 15],
        ];
 
        foreach ($defaults as $row) {
            DB::table('work_schedules')->insert(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_schedules');
    }
};

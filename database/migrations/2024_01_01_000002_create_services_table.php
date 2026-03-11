<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // خدمات الشركة (Social Media, Marketing, Dev, Design, Video...)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // اسم الخدمة بالعربي
            $table->string('name_en')->nullable(); // اسم الخدمة بالانجليزي
            $table->enum('service_type', ['recurring', 'project'])->default('recurring');
            $table->unsignedInteger('estimated_minutes_per_unit')->default(60);             // الوقت المقدّر لإنجاز وحدة واحدة من الخدمة (بالدقائق)
            $table->string('slug')->unique();    // social_media, marketing, development...
            $table->string('color')->default('#2563eb'); // للعرض في الـ UI
            $table->string('icon')->nullable();  // emoji أو icon class
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_services');
        Schema::dropIfExists('services');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->foreignId('coach_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('day_of_week'); // 0=Sun … 6=Sat
            $table->time('time');
            $table->unsignedSmallInteger('capacity')->default(20);
            $table->boolean('is_recurring')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};

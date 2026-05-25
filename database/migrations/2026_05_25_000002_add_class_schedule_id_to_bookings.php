<?php

use App\Models\ClassSchedule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('class_schedule_id')->nullable()->constrained('class_schedules')->nullOnDelete()->after('coach_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropForeignIdFor(ClassSchedule::class);
            $table->dropColumn('class_schedule_id');
        });
    }
};

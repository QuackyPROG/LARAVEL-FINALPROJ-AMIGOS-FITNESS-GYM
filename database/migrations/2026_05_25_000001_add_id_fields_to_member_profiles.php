<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_profiles', function (Blueprint $table): void {
            $table->string('id_type')->nullable()->after('government_id_path');
            $table->string('id_number')->nullable()->after('id_type');
        });
    }

    public function down(): void
    {
        Schema::table('member_profiles', function (Blueprint $table): void {
            $table->dropColumn(['id_type', 'id_number']);
        });
    }
};

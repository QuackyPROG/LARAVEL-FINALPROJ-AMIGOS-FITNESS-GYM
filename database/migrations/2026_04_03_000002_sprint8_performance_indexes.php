<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // AC-03: track when expiry warning was sent to prevent duplicate emails
        Schema::table('memberships', function (Blueprint $table) {
            $table->timestamp('expiry_warned_at')->nullable()->after('status');
        });

        // Indexes for memberships.expires_at, audit_logs.created_at, bookings.scheduled_at
        // are already present from earlier migrations — no-op here.
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('expiry_warned_at');
        });
    }
};

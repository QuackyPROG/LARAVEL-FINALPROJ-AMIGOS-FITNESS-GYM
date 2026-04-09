<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_consents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('document_key', 100);
            $table->unsignedInteger('version');
            $table->string('ip_address', 45);
            $table->string('method', 20)->default('online'); // 'online' | 'staff_witnessed'
            $table->timestamp('accepted_at');
            $table->timestamps();

            $table->index(['user_id', 'document_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_consents');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('checkout_session'); // checkout_session | payment_intent
            $table->string('status')->default('pending');        // pending | paid | failed
            $table->unsignedInteger('amount');                   // in centavos (e.g. 99900 = PHP 999.00)
            $table->char('currency', 3)->default('PHP');
            $table->string('paymongo_id')->unique()->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('paymongo_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

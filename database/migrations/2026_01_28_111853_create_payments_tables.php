<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Midtrans, Xendit, Manual
            $table->string('code')->unique(); // midtrans, xendit, manual
            $table->json('config')->nullable(); // api_key, server_key, secret
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('payment_gateway_id')->constrained('payment_gateways')->cascadeOnDelete();
            $table->string('transaction_id')->nullable(); // ID dari gateway
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, failed, expired
            $table->json('payload')->nullable(); // Response dari gateway
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_gateways');
    }
};

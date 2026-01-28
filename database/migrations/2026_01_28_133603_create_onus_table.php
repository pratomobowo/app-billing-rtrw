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
        Schema::create('onus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('olt_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('serial_number');
            $table->string('interface')->nullable(); // e.g., PON 1/1/1
            $table->decimal('signal', 5, 2)->nullable(); // e.g., -24.50
            $table->timestamp('last_check')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onus');
    }
};

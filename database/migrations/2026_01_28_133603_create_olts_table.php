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
        Schema::create('olts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address')->nullable();
            $table->string('type')->default('ZTE'); // ZTE, HUAWEI, EPON
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('port')->default(23); // Telnet port
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olts');
    }
};

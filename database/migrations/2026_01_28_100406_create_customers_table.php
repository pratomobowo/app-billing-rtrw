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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('whatsapp');
            $table->text('address')->nullable();
            $table->string('connection_type')->default('pppoe'); // pppoe or hotspot
            $table->string('pppoe_user')->nullable();
            $table->string('pppoe_pass')->nullable();
            $table->string('status')->default('active'); // active or isolated
            $table->integer('due_date')->default(5); // day of month (1-31)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->string('establishment_code', 25);
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('email_address', 50);
            $table->string('contact_number', 11);
            $table->string('establishment_name', 50);
            $table->text('address');
            $table->string('baranggay', 20);
            $table->string('city', 20);
            $table->decimal('lng', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->enum('status', ['Normal', 'Infected', 'Sanitized'])->default('Normal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
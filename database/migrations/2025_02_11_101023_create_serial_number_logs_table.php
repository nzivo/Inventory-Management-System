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
        Schema::create('serial_number_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('serial_number_id');
            $table->unsignedBigInteger('user_id');
            $table->text('description');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('serial_number_id')->references('id')->on('serial_numbers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serial_number_logs');
    }
};

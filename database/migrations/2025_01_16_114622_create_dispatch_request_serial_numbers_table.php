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
        Schema::create('dispatch_request_serial_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispatch_request_id'); // Foreign key to dispatch request
            $table->unsignedBigInteger('serial_number_id'); // Foreign key to serial number
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('dispatch_request_id')->references('id')->on('dispatch_requests')->onDelete('cascade');
            $table->foreign('serial_number_id')->references('id')->on('serial_numbers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_request_serial_numbers');
    }
};

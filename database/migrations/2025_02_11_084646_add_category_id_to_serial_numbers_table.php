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
        Schema::table('serial_numbers', function (Blueprint $table) {
            // Adding the 'category_id' column
            $table->unsignedBigInteger('category_id')->nullable()->after('user_id');

            // Optionally, add a foreign key constraint to link category_id to a 'categories' table
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('serial_numbers', function (Blueprint $table) {
            //
        });
    }
};

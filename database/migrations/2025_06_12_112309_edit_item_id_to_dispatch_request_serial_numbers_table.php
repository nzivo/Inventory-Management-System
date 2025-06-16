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
        Schema::table('dispatch_request_serial_numbers', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('serial_number_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispatch_request_serial_numbers', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });
    }
};

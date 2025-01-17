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
        Schema::table(
            'suppliers',
            function (Blueprint $table) {
                $table->string('location')->nullable()->after('name');
                $table->string('postal_address')->nullable()->after('location');
                $table->string('postal_code')->nullable()->after('postal_address');
                $table->string('primary_phone')->nullable()->after('postal_code');
                $table->string('secondary_phone')->nullable()->after('primary_phone');
                $table->string('email')->nullable()->after('secondary_phone');
                $table->string('url')->nullable()->after('email');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_img')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('model_number')->nullable(); // Unique serial number for each item
            $table->integer('quantity')->default(1);
            $table->integer('threshold')->default(1);
            $table->integer('available_quantity')->default(1);
            $table->unsignedBigInteger('category_id');  // Foreign key to the category
            $table->unsignedBigInteger('branch_id');    // Foreign key to the branch
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->enum('status', ['active', 'inactive', 'in_stock', 'out_of_stock'])->default('active'); // Item status
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
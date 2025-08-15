<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_request_batches', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_request_item_id')->constrained('detail_product_requests')->onDelete('cascade');
            $table->foreignId('inventory_in_id')->constrained('inventory_in')->onDelete('cascade');
            $table->integer('allocated_quantity');
            $table->integer('current_stock');
            $table->string('wasted')->default('No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('product_request_batches');
        Schema::enableForeignKeyConstraints();
    }
};

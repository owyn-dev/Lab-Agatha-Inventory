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
        Schema::create('inventory_in', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('batch_code')->unique();
            $table->timestamp('transaction_date');
            $table->string('shelf_name');
            $table->integer('stock_start');
            $table->integer('current_stock');
            $table->decimal('unit_price', 15, 2);
            $table->timestamp('expiration_date');
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
        Schema::dropIfExists('inventory_in');
        Schema::enableForeignKeyConstraints();
    }
};

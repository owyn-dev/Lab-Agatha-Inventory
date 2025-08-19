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
        Schema::create('detail_product_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_request_id')->constrained('product_requests')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('requested_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('detail_product_requests');
        Schema::enableForeignKeyConstraints();
    }
};

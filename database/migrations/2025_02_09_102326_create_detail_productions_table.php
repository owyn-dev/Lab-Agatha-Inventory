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
        Schema::create('detail_productions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('production_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('batch_code')->unique();
            $table->string('shelf_name');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('detail_productions');
        Schema::enableForeignKeyConstraints();
    }
};

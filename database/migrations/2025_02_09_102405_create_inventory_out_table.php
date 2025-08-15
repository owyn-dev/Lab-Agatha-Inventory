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
        Schema::create('inventory_out', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_in_id')->constrained('inventory_in')->onDelete('cascade');
            $table->string('batch_code');
            $table->timestamp('transaction_date');
            $table->string('shelf_name');
            $table->integer('stock_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('inventory_out');
        Schema::enableForeignKeyConstraints();
    }
};

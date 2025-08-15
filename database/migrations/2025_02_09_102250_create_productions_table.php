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
        Schema::create('productions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_user_id')->constrained('users');
            $table->timestamp('production_request_date');
            $table->foreignId('production_user_id')->nullable()->constrained('users');
            $table->timestamp('production_date')->nullable();
            $table->string('status')->default('waiting_for_response');
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('productions');
        Schema::enableForeignKeyConstraints();
    }
};

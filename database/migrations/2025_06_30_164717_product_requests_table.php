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
        Schema::create('product_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sales_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('product_request_date');
            $table->foreignId('handled_by')->nullable()->constrained('users');
            $table->timestamp('handled_date')->nullable();
            $table->enum('status', ['waiting_for_response', 'in_production', 'approved', 'rejected'])->default('waiting_for_response');
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
        Schema::dropIfExists('product_requests');
        Schema::enableForeignKeyConstraints();
    }
};

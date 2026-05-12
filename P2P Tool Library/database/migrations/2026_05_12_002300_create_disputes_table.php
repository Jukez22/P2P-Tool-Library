<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            // Note: Ensuring the reference matches the requested 'reservations' table
            $table->integer('borrow_id'); $table->foreign('borrow_id')->references('id')->on('reservations')->cascadeOnDelete();
            $table->integer('borrower_id'); $table->foreign('borrower_id')->references('id')->on('users')->cascadeOnDelete();
            $table->integer('lender_id'); $table->foreign('lender_id')->references('id')->on('users')->cascadeOnDelete();
            $table->text('dispute_reason');
            $table->enum('dispute_status', ['pending', 'under_review', 'resolved', 'rejected'])->default('pending');
            $table->text('resolution')->nullable();
            $table->integer('librarian_id')->nullable(); $table->foreign('librarian_id')->references('id')->on('users')->nullOnDelete();
            $table->boolean('deposit_forfeited')->default(false);
            $table->decimal('forfeited_amount', 10, 2)->default(0);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};








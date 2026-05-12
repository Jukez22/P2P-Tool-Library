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
            // Note: Ensuring the reference matches the requested 'borrows' table
            $table->foreignId('borrow_id')->constrained('borrows')->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lender_id')->constrained('users')->cascadeOnDelete();
            $table->text('dispute_reason');
            $table->enum('dispute_status', ['pending', 'under_review', 'resolved', 'rejected'])->default('pending');
            $table->text('resolution')->nullable();
            $table->foreignId('librarian_id')->nullable()->constrained('users')->nullOnDelete();
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

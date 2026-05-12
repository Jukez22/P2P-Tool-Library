<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lender_id')->constrained('users')->cascadeOnDelete();
            $table->enum('audit_status', ['pending', 'submitted', 'approved', 'rejected', 'expired'])->default('pending');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('inventory_audits');
    }
};

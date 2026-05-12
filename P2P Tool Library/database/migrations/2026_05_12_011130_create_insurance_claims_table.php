<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->integer('borrow_id'); $table->foreign('borrow_id')->references('id')->on('reservations')->cascadeOnDelete();
            $table->integer('tool_id'); $table->foreign('tool_id')->references('id')->on('tools')->cascadeOnDelete();
            $table->integer('claimant_id'); $table->foreign('claimant_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('claim_type', ['theft', 'total_destruction']);
            $table->enum('claim_status', ['pending', 'under_review', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('incident_description');
            $table->decimal('estimated_loss', 10, 2);
            $table->string('insurance_report')->nullable();
            $table->integer('reviewed_by')->nullable(); $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
    }
};








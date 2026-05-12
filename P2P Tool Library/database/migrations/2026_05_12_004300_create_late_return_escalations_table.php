<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('late_return_escalations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_id')->constrained('borrows')->cascadeOnDelete();
            $table->enum('escalation_level', ['warning', 'penalty_level_1', 'penalty_level_2', 'final_notice']);
            $table->integer('days_late')->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->boolean('notification_sent')->default(false);
            $table->timestamp('escalated_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('late_return_escalations');
    }
};

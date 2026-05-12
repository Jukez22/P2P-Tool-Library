<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('dashboard_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('activity_type', [
                'rental_started',
                'rental_completed',
                'pending_return',
                'overdue_return',
                'tool_reserved',
                'payment_received'
            ]);
            $table->foreignId('borrow_id')->nullable()->constrained('borrows')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tool_id')->nullable()->constrained('tools')->nullOnDelete();
            $table->text('activity_message');
            $table->timestamp('activity_time');
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('dashboard_activity_logs');
    }
};

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
            $table->integer('borrow_id')->nullable(); $table->foreign('borrow_id')->references('id')->on('reservations')->nullOnDelete();
            $table->integer('user_id')->nullable(); $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->integer('tool_id')->nullable(); $table->foreign('tool_id')->references('id')->on('tools')->nullOnDelete();
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








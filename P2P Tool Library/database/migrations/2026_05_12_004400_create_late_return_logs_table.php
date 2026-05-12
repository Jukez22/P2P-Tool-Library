<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('late_return_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('late_return_escalation_id'); $table->foreign('late_return_escalation_id')->references('id')->on('late_return_escalations')
                ->cascadeOnDelete();
            $table->enum('notification_type', ['email', 'sms']);
            $table->text('message');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('late_return_logs');
    }
};








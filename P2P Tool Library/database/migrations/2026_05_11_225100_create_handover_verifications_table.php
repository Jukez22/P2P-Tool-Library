<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('handover_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_id')->constrained('borrows')->cascadeOnDelete();
            $table->string('qr_code')->unique();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('handover_verifications');
    }
};

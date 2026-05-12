<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_audit_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_audit_id'); $table->foreign('inventory_audit_id')->references('id')->on('inventory_audits')->cascadeOnDelete();
            $table->integer('tool_id'); $table->foreign('tool_id')->references('id')->on('tools')->cascadeOnDelete();
            $table->string('proof_image')->nullable();
            $table->string('proof_video')->nullable();
            $table->enum('item_status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audit_items');
    }
};








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
        Schema::create('dispute_evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispute_id'); $table->foreign('dispute_id')->references('id')->on('disputes')->cascadeOnDelete();
            $table->integer('uploaded_by'); $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('evidence_type', ['image', 'video', 'log']);
            $table->string('file_path')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispute_evidences');
    }
};








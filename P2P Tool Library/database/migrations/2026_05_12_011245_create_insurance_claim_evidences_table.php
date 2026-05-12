<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        Schema::create('insurance_claim_evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insurance_claim_id'); $table->foreign('insurance_claim_id')->references('id')->on('insurance_claims')
                ->cascadeOnDelete();
            $table->enum('evidence_type', ['image', 'video', 'police_report', 'receipt', 'other']);
            $table->string('file_path');
            $table->integer('uploaded_by'); $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('insurance_claim_evidences');
    }
};








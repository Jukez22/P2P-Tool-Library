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
            $table->foreignId('insurance_claim_id')
                ->constrained('insurance_claims')
                ->cascadeOnDelete();
            $table->enum('evidence_type', ['image', 'video', 'police_report', 'receipt', 'other']);
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    // Reverse migrations
    public function down(): void
    {
        Schema::dropIfExists('insurance_claim_evidences');
    }
};

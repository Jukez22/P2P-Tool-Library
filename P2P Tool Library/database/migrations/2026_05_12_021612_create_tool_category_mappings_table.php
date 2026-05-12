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
        Schema::create('tool_category_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('tool_id'); $table->foreign('tool_id')->references('id')->on('tools')->cascadeOnDelete();
            $table->unsignedBigInteger('tool_category_id'); $table->foreign('tool_category_id')->references('id')->on('tool_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_category_mappings');
    }
};








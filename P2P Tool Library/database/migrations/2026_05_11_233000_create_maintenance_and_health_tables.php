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
        // Add maintenance fields to tools table
        if (Schema::hasTable('tools')) {
            Schema::table('tools', function (Blueprint $table) {
                $table->integer('usage_count')->default(0);
                $table->integer('maintenance_interval_uses')->default(5);
                $table->boolean('needs_inspection')->default(false);
                $table->date('safety_cert_expiry_date')->nullable();
                $table->date('warranty_expiry_date')->nullable();
                $table->boolean('is_unfit')->default(false);
            });
        }

        // Create repair_cost_estimates table
        if (!Schema::hasTable('repair_cost_estimates')) {
            Schema::create('repair_cost_estimates', function (Blueprint $table) {
                $table->id();
                $table->string('issue_name');
                $table->decimal('estimated_cost', 10, 2);
                $table->integer('category_id');
                $table->timestamps();
            });
        }

        // Create consumables table
        if (!Schema::hasTable('consumables')) {
            Schema::create('consumables', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('stock_level')->default(0);
                $table->integer('reorder_threshold')->default(10);
                $table->timestamps();
            });
        }

        // Create external_repairs table
        if (!Schema::hasTable('external_repairs')) {
            Schema::create('external_repairs', function (Blueprint $table) {
                $table->id();
                $table->integer('tool_id');
                $table->integer('maintenance_log_id')->nullable();
                $table->string('shop_name');
                $table->date('dispatch_date');
                $table->date('expected_return_date')->nullable();
                $table->enum('status', ['dispatched', 'in_repair', 'returned'])->default('dispatched');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_repairs');
        Schema::dropIfExists('consumables');
        Schema::dropIfExists('repair_cost_estimates');

        if (Schema::hasTable('tools')) {
            Schema::table('tools', function (Blueprint $table) {
                $table->dropColumn([
                    'usage_count',
                    'maintenance_interval_uses',
                    'needs_inspection',
                    'safety_cert_expiry_date',
                    'warranty_expiry_date',
                    'is_unfit'
                ]);
            });
        }
    }
};








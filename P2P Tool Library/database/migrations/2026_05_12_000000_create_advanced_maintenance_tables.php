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
        // Modify maintenance_logs table
        if (Schema::hasTable('maintenance_logs')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->boolean('is_successful')->default(true);
            });
        }

        // Create diagnostic_articles table
        if (!Schema::hasTable('diagnostic_articles')) {
            Schema::create('diagnostic_articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->integer('author_id'); // maps to users table technician
                $table->timestamps();
            });
        }

        // Create battery_health_logs table
        if (!Schema::hasTable('battery_health_logs')) {
            Schema::create('battery_health_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('tool_id');
                $table->integer('charge_cycles')->default(0);
                $table->integer('health_percentage');
                $table->timestamp('logged_at')->useCurrent();
                $table->timestamps();
            });
        }

        // Create disposals table
        if (!Schema::hasTable('disposals')) {
            Schema::create('disposals', function (Blueprint $table) {
                $table->id();
                $table->integer('tool_id');
                $table->string('reason');
                $table->enum('disposal_method', ['recycle', 'trashed', 'donated']);
                $table->date('disposed_at');
                $table->timestamps();
            });
        }

        // Create spare_part_orders table
        if (!Schema::hasTable('spare_part_orders')) {
            Schema::create('spare_part_orders', function (Blueprint $table) {
                $table->id();
                $table->integer('tool_id');
                $table->string('part_name');
                $table->date('order_date');
                $table->date('expected_arrival_date')->nullable();
                $table->enum('status', ['ordered', 'arrived', 'installed'])->default('ordered');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_orders');
        Schema::dropIfExists('disposals');
        Schema::dropIfExists('battery_health_logs');
        Schema::dropIfExists('diagnostic_articles');

        if (Schema::hasTable('maintenance_logs')) {
            Schema::table('maintenance_logs', function (Blueprint $table) {
                $table->dropColumn([
                    'started_at',
                    'completed_at',
                    'is_successful'
                ]);
            });
        }
    }
};

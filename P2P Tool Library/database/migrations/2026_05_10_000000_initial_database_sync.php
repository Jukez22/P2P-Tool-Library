<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Run migrations
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name', 70);
                $table->integer('parent_id');
            });
        }

        if (!Schema::hasTable('membership_tiers')) {
            Schema::create('membership_tiers', function (Blueprint $table) {
                $table->integer('id', true);
                $table->decimal('discount_rate', 5, 2);
                $table->integer('boost_limit');
                $table->enum('name', ['casual', 'pro', 'premium', '']);
                $table->integer('max_active_rentals');
            });
        }

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name', 50);
                $table->string('phone', 20);
                $table->string('email', 50)->unique();
                $table->string('password');
                $table->enum('role', ['lender', 'borrower', 'librarian', 'technician']);
                $table->string('address', 255)->nullable();
                $table->integer('membership_tier_id');
                $table->decimal('trust_score', 3, 1)->default(3.0);
                $table->rememberToken();
                $table->timestamp('created_at')->useCurrent();
                $table->foreign('membership_tier_id')->references('id')->on('membership_tiers');
            });
        }

        if (!Schema::hasTable('tools')) {
            Schema::create('tools', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('title');
                $table->decimal('price', 8, 2);
                $table->text('description');
                $table->enum('condition_status', ['Excellent', 'Good', 'Fair', 'Needs Repair']);
                $table->boolean('is_boosted');
                $table->decimal('location_lng', 10, 7)->nullable();
                $table->decimal('location_lat', 10, 7)->nullable();
                $table->integer('category_id');
                $table->integer('owner_id');
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('owner_id')->references('id')->on('users');
            });
        }

        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->integer('id', true);
                $table->dateTime('start_datetime');
                $table->dateTime('end_datetime');
                $table->integer('borrower_id');
                $table->enum('status', ['Pending', 'Active', 'Confirmed', 'Completed', 'Cancelled']);
                $table->integer('tool_id');
                $table->decimal('total_price', 10, 2);
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('deposits')) {
            Schema::create('deposits', function (Blueprint $table) {
                $table->integer('id', true);
                $table->decimal('amount', 10, 2);
                $table->timestamp('created_at')->useCurrent();
                $table->integer('reservation_id');
                $table->enum('status', ['released', 'held', 'forfeited', '']);
            });
        }

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('reservation_id');
                $table->decimal('amount', 10, 2);
                $table->enum('payment_method', ['cash', 'card', 'wallet', '']);
                $table->timestamp('created_at')->useCurrent();
                $table->enum('status', ['paid', 'pending', 'failed', 'refunded']);
            });
        }

        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->integer('id', true);
                $table->decimal('rating', 2, 1);
                $table->text('comment');
                $table->integer('reviewer_user_id');
                $table->integer('reviewed_user_id');
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('tool_availability')) {
            Schema::create('tool_availability', function (Blueprint $table) {
                $table->integer('id', true);
                $table->dateTime('start_datetime');
                $table->dateTime('end_datetime');
                $table->integer('tool_id');
                $table->enum('status', ['available', 'buffer', 'blocked', '']);
            });
        }

        if (!Schema::hasTable('tool_documents')) {
            Schema::create('tool_documents', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('file_url', 255);
                $table->enum('type', ['manual', 'video', 'warranty', '']);
                $table->integer('tool_id');
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('maintenance_logs')) {
            Schema::create('maintenance_logs', function (Blueprint $table) {
                $table->integer('id', true);
                $table->text('description');
                $table->date('date');
                $table->decimal('cost', 10, 2);
                $table->integer('tool_id');
                $table->enum('status', ['scheduled', 'in-progress', 'done', '']);
                $table->integer('technician_id');
                $table->enum('type', ['repair', 'inspection', 'cleaning', '']);
            });
        }

        if (!Schema::hasTable('referrals')) {
            Schema::create('referrals', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('referrer_user_id');
                $table->integer('referred_user_id');
                $table->decimal('reward', 10, 2);
                $table->enum('status', ['pending', 'completed', 'expired', '']);
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('trust_score_logs')) {
            Schema::create('trust_score_logs', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->integer('change_value');
                $table->string('reason', 225);
                $table->timestamp('created_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('verifications')) {
            Schema::create('verifications', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->string('national_id', 20);
                $table->enum('status', ['verified', 'rejected', 'pending', '']);
                $table->timestamp('verified_at')->useCurrent();
            });
        }

        if (!Schema::hasTable('zones')) {
            Schema::create('zones', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('name', 70);
                $table->string('city', 70);
                $table->string('country', 70);
            });
        }
        
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('reporter_id');
                $table->integer('reported_user_id')->nullable();
                $table->integer('reported_tool_id')->nullable();
                $table->integer('reservation_id')->nullable();
                $table->enum('reason', ['damaged_tool', 'no_show', 'fraud', 'late_return', 'other']);
                $table->text('description');
                $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
                $table->timestamp('created_at')->useCurrent();
            });
        }




    }

    // Reverse migrations
    public function down(): void
    {
        // Keep as is for safety
    }
};

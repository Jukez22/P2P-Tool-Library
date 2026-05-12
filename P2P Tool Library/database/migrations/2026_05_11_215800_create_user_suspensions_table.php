<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateUserSuspensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_suspensions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); $table->foreign('user_id')->references('id')->on('users')
                  ->cascadeOnDelete();
            $table->enum('type', ['temporary', 'permanent']);
            $table->text('reason');
            $table->timestamp('suspended_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('created_by'); $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_suspensions');
    }
}








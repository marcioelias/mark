<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('funnel_step_action_id');
            $table->uuid('transaction_id');
            $table->datetime('start_at');
            $table->time('start_period')->nullable();
            $table->time('end_period')->nullable();
            $table->time('delay_before_start')->nullable();
            $table->datetime('queued_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('funnel_step_action_id')->references('id')->on('funnel_step_actions');
            $table->foreign('transaction_id')->references('id')->on('transactions');
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
        Schema::dropIfExists('shedules');
    }
}

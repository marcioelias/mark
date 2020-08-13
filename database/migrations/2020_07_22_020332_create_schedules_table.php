<?php

use App\Constants\ScheduleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('lead_id');
            $table->uuid('funnel_step_id');
            $table->uuid('funnel_step_action_id');
            $table->datetime('start_at');
            $table->time('start_period')->nullable();
            $table->time('end_period')->nullable();
            $table->time('delay_before_start')->nullable();
            $table->datetime('queued_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->uuid('schedule_status_id')->default(ScheduleStatus::PENDING);
            $table->string('result_message')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->foreign('funnel_step_id')->references('id')->on('funnel_steps')->onDelete('cascade');
            $table->foreign('funnel_step_action_id')->references('id')->on('funnel_step_actions')->onDelete('cascade');
            $table->foreign('schedule_status_id')->references('id')->on('schedule_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}

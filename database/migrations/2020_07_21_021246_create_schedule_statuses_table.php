<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateScheduleStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('schedule_status');
            $table->timestamps();
        });

        Artisan::call('db:seed', array('--class' => ScheduleStatusesTableSeeder::class));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_statuses');
    }
}

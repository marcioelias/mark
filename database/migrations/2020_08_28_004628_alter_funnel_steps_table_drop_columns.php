<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFunnelStepsTableDropColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funnel_steps', function (Blueprint $table) {
            $table->dropColumn('funnel_step_sequence');
            $table->dropColumn('funnel_step_description');
            $table->dropColumn('delay_days');
            $table->dropColumn('delay_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funnel_steps', function (Blueprint $table) {
            $table->unsignedInteger('funnel_step_sequence')->after('funnel_id');
            $table->string('funnel_step_description')->after('funnel_step_sequence');
            $table->unsignedInteger('delay_days')->default(0)->after('funnel_step_description');
            $table->unsignedInteger('delay_hours')->default(0)->after('delay_days');
        });
    }
}

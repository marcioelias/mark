<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFunnelStepsTableDropFunnelStepSequenceColumn extends Migration
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
        });
    }
}

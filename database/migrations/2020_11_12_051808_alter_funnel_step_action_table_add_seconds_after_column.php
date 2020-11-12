<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFunnelStepActionTableAddSecondsAfterColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funnel_step_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('seconds_after')->default(0)->after('action_sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funnel_step_actions', function (Blueprint $table) {
            $table->dropColumn('seconds_after');
        });
    }
}

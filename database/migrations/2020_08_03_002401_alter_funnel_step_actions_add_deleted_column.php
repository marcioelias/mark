<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFunnelStepActionsAddDeletedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funnel_step_actions', function (Blueprint $table) {
            $table->boolean('deleted')->default(false)->after('action_data');
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
            $table->dropColumn('deleted');
        });
    }
}

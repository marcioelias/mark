<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddColumnFunnelStepId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->uuid('funnel_step_id')->nullable()->after('tag_id');
            $table->foreign('funnel_step_id')->references('id')->on('funnel_steps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_funnel_step_id_foreign');
            $table->dropColumn('funnel_step_id');
        });
    }
}

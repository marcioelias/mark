<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFunnelStepsTableAddPostbackEventTypeIdForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('funnel_steps', function (Blueprint $table) {
            $table->uuid('postback_event_type_id')->nullable()->after('funnel_id');
            $table->foreign('postback_event_type_id')->references('id')->on('postback_event_types');
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
            $table->dropForeign('funnel_steps_postback_event_type_id_foreign');
            $table->dropColumn('postback_event_type_id');
        });
    }
}

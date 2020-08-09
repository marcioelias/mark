<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPostbacksTableAddPostbackEventTypeForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('postbacks', function (Blueprint $table) {
            $table->dropColumn('event_type');
            $table->uuid('postback_event_type_id')->after('lead_id');
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
        Schema::table('postbacks', function (Blueprint $table) {
            $table->dropForeign('postbacks_postback_event_type_id_foreign');
            $table->dropColumn('postback_event_type_id');
            $table->string('event_type')->nullable()->after('lead_id');
        });
    }
}

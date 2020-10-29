<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMarketingActionsTableAddStartAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_actions', function (Blueprint $table) {
            $table->datetime('start_at')->nullable()->after('marketing_action_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketing_actions', function (Blueprint $table) {
            $table->dropColumn('start_at');
        });
    }
}

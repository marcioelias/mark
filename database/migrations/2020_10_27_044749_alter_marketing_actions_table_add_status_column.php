<?php

use App\Constants\MarketingActionStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMarketingActionsTableAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_actions', function (Blueprint $table) {
            $table->uuid('marketing_action_status_id')->default(MarketingActionStatuses::PENDING)->after('action_message');
            $table->foreign('marketing_action_status_id')->references('id')->on('marketing_action_statuses');
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
            $table->dropForeign('marketing_actions_marketing_action_status_id_foreign');
            $table->dropColumn('marketing_action_status_id');
        });
    }
}

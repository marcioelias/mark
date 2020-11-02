<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableDropPlanCycleColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('plan_cycle_begins');
            $table->dropColumn('plan_cycle_ends');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('plan_cycle_begins')->useCurrent()->after('active');
            $table->timestamp('plan_cycle_ends')->useCurrent()->after('plan_cycle_begins');
        });
    }
}

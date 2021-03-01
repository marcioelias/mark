<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSmsUserTransactionsAddPaidAmountColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_user_transactions', function (Blueprint $table) {
            $table->double('paid_amount', 19, 2)->nullable()->after('sms_package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_user_transactions', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
}

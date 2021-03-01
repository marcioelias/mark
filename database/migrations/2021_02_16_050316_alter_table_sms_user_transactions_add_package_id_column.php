<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSmsUserTransactionsAddPackageIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_user_transactions', function (Blueprint $table) {
            $table->uuid('sms_package_id')->nullable()->before('obs');
            $table->foreign('sms_package_id')->references('id')->on('sms_packages')->nullOnDelete();
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
            $table->dropForeign('sms_user_transactions_sms_package_id_foreign');
            $table->dropColumn('sms_package_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSmsStocksAddSaleIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_stocks', function (Blueprint $table) {
            $table->uuid('sms_sale_id')->nullable()->after('sms_buy_id');
            $table->uuid('sms_buy_id')->nullable()->change();
            $table->foreign('sms_sale_id')->references('id')->on('sms_sales')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_stocks', function (Blueprint $table) {
            $table->dropForeign('sms_stocks_sms_sale_id_foreign');
            $table->uuid('sms_buy_id')->nullable(false)->change();
            $table->dropColumn('sms_sale_id');
        });
    }
}

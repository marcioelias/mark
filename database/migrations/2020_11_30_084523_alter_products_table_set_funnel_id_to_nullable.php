<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsTableSetFunnelIdToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->uuid('funnel_id')->nullable()->change();
            $table->dropForeign('products_funnel_id_foreign');
            $table->foreign('funnel_id')->references('id')->on('funnels')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->uuid('funnel_id')->nullable(false)->change();
            $table->dropForeign('products_funnel_id_foreign');
            $table->foreign('funnel_id')->references('id')->on('funnels');
        });
    }
}

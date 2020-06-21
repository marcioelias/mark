<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProductsDropFkPlataformAddFkPlataformConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_plataform_id_foreign');
            $table->dropColumn('plataform_id');
            $table->uuid('plataform_config_id')->after('user_id');
            $table->foreign('plataform_config_id')->references('id')->on('plataform_configs');
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
            $table->dropForeign('products_plataform_config_id_foreign');
            $table->dropColumn('plataform_config_id');
            $table->uuid('plataform_id')->after('user_id');
            $table->foreign('plataform_id')->references('id')->on('plataforms');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFeaturesTableAddColumnActionTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->uuid('action_type_id')->nullable()->after('feature');
            $table->foreign('action_type_id')->references('id')->on('action_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropForeign('features_action_type_id_foreign');
            $table->dropColumn('action_type_id');
        });
    }
}

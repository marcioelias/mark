<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_tag_id_foreign');
            $table->dropColumn('tag_id');
        });

        Schema::table('funnel_steps', function (Blueprint $table) {
            $table->dropForeign('funnel_steps_new_tag_id_foreign');
            $table->dropColumn('new_tag_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIfExists();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('tag_name');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->uuid('tag_id')->after('lead_status_id');
            $table->foreign('tag_id')->references('id')->on('tags');
        });

        Schema::table('funnel_steps', function (Blueprint $table) {
            $table->uuid('new_tag_id')->after('funnel_step_description');
            $table->foreign('new_tag_id')->references('id')->on('tags');
        });
    }
}

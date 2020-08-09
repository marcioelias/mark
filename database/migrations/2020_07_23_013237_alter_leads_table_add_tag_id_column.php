<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddTagIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            //$table->dropColumn('status');
            $table->uuid('lead_status_id')->nullable()->after('paid_at');
            $table->uuid('tag_id')->nullable()->after('lead_status_id');
            $table->foreign('lead_status_id')->references('id')->on('lead_statuses');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_tag_id_foreign');
            $table->dropForeign('leads_lead_status_id_foreign');
            $table->dropColumn('tag_id');
            $table->dropColumn('lead_status_id');
            $table->string('status')->nullable()->after('paid_at');
        });
    }
}

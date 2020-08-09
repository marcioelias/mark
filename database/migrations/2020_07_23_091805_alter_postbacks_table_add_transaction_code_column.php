<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPostbacksTableAddTransactionCodeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('postbacks', function (Blueprint $table) {
            $table->string('transaction_code')->index()->after('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('postbacks', function (Blueprint $table) {
            $table->dropColumn('transaction_code');
        });
    }
}

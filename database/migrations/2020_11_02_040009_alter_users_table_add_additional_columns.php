<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddAdditionalColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('doc_type')->default('CPF')->after('last_name');
            $table->string('doc_number')->nullable()->after('doc_type');
            $table->string('zip_code')->nullable()->after('doc_number');
            $table->string('street_name')->nullable()->after('zip_code');
            $table->string('street_number')->nullable()->after('street_name');
            $table->string('neighborhood')->nullable()->after('street_number');
            $table->string('city')->nullable()->after('neighborhood');
            $table->string('state')->nullable()->after('city');
            $table->integer('phone_area')->nullable()->before('phone_number');
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
            $table->dropColumn('last_name');
            $table->dropColumn('doc_type');
            $table->dropColumn('doc_number');
            $table->dropColumn('zip_code');
            $table->dropColumn('street_name');
            $table->dropColumn('street_number');
            $table->dropColumn('neighborhood');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('phone_area');
        });
    }
}

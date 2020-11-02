<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSmsPackagesTableAddAdditionalColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_packages', function (Blueprint $table) {
            $table->string('sms_package_description')->nullable()->after('sms_package_name');
            $table->string('category')->default('others')->after('sms_package_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_packages', function (Blueprint $table) {
            $table->dropColumn('sms_package_description');
            $table->dropColumn('category');
        });
    }
}

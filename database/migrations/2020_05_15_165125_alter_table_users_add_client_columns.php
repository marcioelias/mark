<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAddClientColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->uuid('plan_id')->nullable()->after('phone_number');
            $table->string('customer_code')->nullable()->after('phone_number');
            $table->foreign('plan_id')->references('id')->on('plans');
            $table->boolean('active')->default(true)->after('remember_token');
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
            $table->dropColumn('active');
            $table->dropForeign('users_plan_id_foreign');
            $table->dropColumn('plan_id');
            $table->dropColumn('phone_number');
            $table->dropColumn('customer_code');
        });
    }
}

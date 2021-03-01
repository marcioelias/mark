<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('sms_package_id');
            $table->uuid('sms_user_transaction_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sms_package_id')->references('id')->on('sms_packages');
            $table->foreign('sms_user_transaction_id')->references('id')->on('sms_user_transactions')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_sales');
    }
}

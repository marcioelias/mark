<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('amount');
            $table->boolean('move_in')->default(true);
            $table->uuid('sms_buy_id');
            $table->foreign('sms_buy_id')->references('id')->on('sms_buys')->onDelete('cascade');
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
        Schema::dropIfExists('sms_stocks');
    }
}

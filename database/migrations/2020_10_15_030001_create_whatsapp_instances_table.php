<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('description');
            $table->uuid('product_id')->nullable();
            $table->unsignedInteger('port');
            $table->string('url')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('hash')->nullable();
            $table->uuid('whatsapp_instance_status_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('whatsapp_instance_status_id')->references('id')->on('whatsapp_instance_statuses');
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
        Schema::dropIfExists('whatsapp_instances');
    }
}

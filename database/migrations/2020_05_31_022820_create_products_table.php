<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('plataform_id');
            $table->string('plataform_code');
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->boolean('active')->default(true);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('plataform_id')->references('id')->on('plataforms');
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
        Schema::dropIfExists('products');
    }
}

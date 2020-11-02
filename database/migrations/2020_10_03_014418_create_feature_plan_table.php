<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_plan', function (Blueprint $table) {
            $table->uuid('feature_id');
            $table->uuid('plan_id');
            $table->boolean('enabled')->default(false);
            $table->unsignedInteger('limit')->default(0);
            $table->foreign('feature_id')->references('id')->on('features')->onCascade('delete');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->primary(['feature_id', 'plan_id']);
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
        Schema::dropIfExists('feature_plan');
    }
}

<?php

use App\Traits\MultiTenantable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunnelStepsTable extends Migration
{
    use MultiTenantable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funnel_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('funnel_id');
            $table->unsignedInteger('sequence')->default(1);
            $table->uuid('original_tag')->nullable();
            $table->uuid('new_tag')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('funnel_id')->references('id')->on('funnels');
            $table->foreign('original_tag')->references('id')->on('tags');
            $table->foreign('new_tag')->references('id')->on('tags');
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
        Schema::dropIfExists('funnel_steps');
    }
}

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
            $table->unsignedInteger('funnel_step_sequence');
            $table->string('funnel_step_description');
            $table->uuid('new_tag_id')->nullable();
            $table->unsignedInteger('delay_days')->default(0);
            $table->unsignedInteger('delay_hours')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('funnel_id')->references('id')->on('funnels')->onDelete('cascade');
            $table->foreign('new_tag_id')->references('id')->on('tags');
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

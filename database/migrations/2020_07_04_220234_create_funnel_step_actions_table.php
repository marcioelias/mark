<?php

use App\Traits\MultiTenantable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunnelStepActionsTable extends Migration
{
    use MultiTenantable;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funnel_step_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('funnel_step_id');
            $table->uuid('action_type_id');
            $table->unsignedInteger('action_sequence');
            $table->json('action_data')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('funnel_step_id')->references('id')->on('funnel_steps');
            $table->foreign('action_type_id')->references('id')->on('action_types');
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
        Schema::dropIfExists('funnel_step_actions');
    }
}

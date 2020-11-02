<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMarketingActionPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_marketing_action', function (Blueprint $table) {
            $table->uuid('customer_id');
            $table->uuid('marketing_action_id');
            $table->datetime('schedule_date');
            $table->datetime('finished_at')->nullable();
            $table->boolean('result_ok')->nullable();
            $table->text('result_message')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('marketing_action_id')->references('id')->on('marketing_actions')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['customer_id', 'marketing_action_id'], 'customer_marketing_action_idx_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_marketing_action');
    }
}

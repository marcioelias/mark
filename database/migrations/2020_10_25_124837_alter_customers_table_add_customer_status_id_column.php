<?php

use App\Constants\CustomerStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomersTableAddCustomerStatusIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->uuid('customer_status_id')->default(CustomerStatuses::INACTIVE)->after('customer_email');
            $table->foreign('customer_status_id')->references('id')->on('customer_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_customer_status_id_foreign');
            $table->dropColumn('customer_status_id');
        });
    }
}

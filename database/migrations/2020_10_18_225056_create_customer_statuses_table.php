<?php

use App\Constants\CustomerStatuses;
use App\Models\User\CustomerStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('customer_status');
            $table->timestamps();
        });

        $this->seedCustomerStatuses();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_statuses');
    }

    private function seedCustomerStatuses() {
        $customerStatuses = [
            [
                'id' => CustomerStatuses::ACTIVE,
                'customer_status' => 'Ativo'
            ],
            [
                'id' => CustomerStatuses::INACTIVE,
                'customer_status' => 'Inativo'
            ],
            [
                'id' => CustomerStatuses::REMARKETING,
                'customer_status' => 'Remarketing'
            ],
        ];

        foreach ($customerStatuses as $customerStatus) {
            CustomerStatus::create($customerStatus);
        }
    }
}

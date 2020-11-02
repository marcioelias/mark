<?php

use App\Constants\CustomerStatuses;
use App\Models\User\CustomerStatus;
use Illuminate\Database\Seeder;

class CustomerStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

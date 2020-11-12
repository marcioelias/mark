<?php

use App\Constants\TransactionTypes;
use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactionTypes = [
            [
                'id' => TransactionTypes::IN,
                'transaction_type' => 'Entrada'
            ],
            [
                'id' => TransactionTypes::OUT,
                'transaction_type' => 'Saída'
            ]
        ];

        foreach ($transactionTypes as $transactionType) {
            TransactionType::create($transactionType);
        }
    }
}

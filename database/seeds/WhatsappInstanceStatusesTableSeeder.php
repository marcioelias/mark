<?php

use App\Constants\WppInstStatuses;
use App\Models\WhatsappInstanceStatus;
use Illuminate\Database\Seeder;

class WhatsappInstanceStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'id' => WppInstStatuses::PENDING,
                'whatsapp_instance_status' => 'Pendente'
            ],
            [
                'id' => WppInstStatuses::DISCONNECTED,
                'whatsapp_instance_status' => 'Desconectada'
            ],
            [
                'id' => WppInstStatuses::CONNECTED,
                'whatsapp_instance_status' => 'Conectada'
            ],
            [
                'id' => WppInstStatuses::DISABLED,
                'whatsapp_instance_status' => 'Desabilitada'
            ],
        ];

        foreach ($statuses as $status) {
            WhatsappInstanceStatus::create($status);
        }
    }
}

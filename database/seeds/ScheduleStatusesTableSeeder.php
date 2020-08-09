<?php

use App\Models\ScheduleStatus;
use Illuminate\Database\Seeder;

class ScheduleStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scheduleStatuses = [
            [
                'id' => '0cebb93c-f5aa-4443-8fc7-a484bda859c3',
                'schedule_status' => 'Pendente',
            ],
            [
                'id' => 'e0f575b9-9059-4243-917b-e842dd684287',
                'schedule_status' => 'Executado',
            ],
            [
                'id' => 'abda429b-11c6-4de8-9f35-171f85b31bcf',
                'schedule_status' => 'Erro',
            ],
            [
                'id' => '90b0030a-2206-4add-9307-6fed6eee743f',
                'schedule_status' => 'Cancelado',
            ]
        ];

        foreach ($scheduleStatuses as $scheduleStatus) {
            ScheduleStatus::create($scheduleStatus);
        }
    }
}

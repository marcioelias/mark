<?php

namespace App\Console\Commands;

use App\Events\NewRunnableAction;
use App\Models\User\FunnelStepAction;
use App\Models\User\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class QueueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotzz:queue-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coloca notificações na fila para envio';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $schedules = Schedule::runnable()->notQueued()->pending()->get();
        foreach($schedules as $schedule) {
            //Log::debug($schedule);
            $schedule->queued_at = Carbon::now();
            $schedule->save();

            event(new NewRunnableAction($schedule));
        }
    }
}

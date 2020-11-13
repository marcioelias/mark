<?php

namespace App\Console\Commands;

use App\Constants\ActionTypes;
use App\Constants\MarketingActionStatuses;
use App\Constants\TransactionTypes;
use App\Events\NewRunnableAction;
use App\Jobs\SendMarketingAction;
use App\Models\User;
use App\Models\User\Customer;
use App\Models\User\FunnelStepAction;
use App\Models\User\MarketingAction;
use App\Models\User\Schedule;
use App\Models\User\SmsUserTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        $this->handleSchedules();
        $this->handleMarketingActions();
    }

    private function handleSchedules() {
        $schedules = Schedule::runnable()->notQueued()->pending()->get();
        foreach($schedules as $schedule) {
            if ($this->hasBalance($schedule)) {

                DB::beginTransaction();
                try {
                    $schedule->queued_at = Carbon::now();
                    $schedule->save();

                    /* Se for disparo de SMS lança uma transação de saida de 1 unidade */
                    if ((string) $schedule->funnelStepAction->action_type_id === (string) ActionTypes::SMS) {
                        SmsUserTransaction::create([
                            'user_id' => $schedule->user_id,
                            'quantity' => 1,
                            'transaction_type_id' => TransactionTypes::OUT
                        ]);
                    }

                    DB::commit();

                    event(new NewRunnableAction($schedule));

                } catch (Exception $e) {
                    DB::rollBack();
                    Log::emergency($e->getMessage());
                }
            }
        }
    }

    private function handleMarketingActions() {
        $marketingActions = MarketingAction::pending()
                                ->where('start_at', '<=', now())
                                ->get();

        foreach ($marketingActions as $marketingAction) {
            if ($marketingAction->action_type_id == ActionTypes::WHATSAPP) {
                $customers = $marketingAction->customers()->wherePivot('finished_at', null)->limit(10)->get();
                foreach ($customers as $customer) {
                    SendMarketingAction::dispatch($marketingAction, $customer);
                }
            } else {
                foreach ($marketingAction->customers()->get() as $customer) {
                    SendMarketingAction::dispatch($marketingAction, $customer);
                }
            }
        }
    }

    private function hasBalance(Schedule $schedule) {
        switch ($schedule->funnelStepAction->action_type_id) {
            case ActionTypes::SMS:
                return $this->hasBalanceSms($schedule->user);
                break;

            default:
                return true;
                break;
        }
    }

    private function hasBalanceSms(User $user) {
        return SmsUserTransaction::SmsAvailable($user);
    }

    private function sendMessage(MarketingAction $marketingAction, Customer $customer) {
        switch ($marketingAction->action_type_id) {
            case ActionTypes::EMAIL:
                $this->sendEmailMessage($marketingAction, $customer);
                break;
            case ActionTypes::SMS:
                $this->sendSMSMessage($marketingAction, $customer);
                break;
            case ActionTypes::WHATSAPP:
                $this->sendWhatsappMessage($marketingAction, $customer);
                break;
        }
    }

    private function sendEmailMessage(MarketingAction $marketingAction, Customer $customer) {

    }
    private function sendSMSMessage(MarketingAction $marketingAction, Customer $customer) {

    }
    private function sendWhatsappMessage(MarketingAction $marketingAction, Customer $customer) {

    }
}

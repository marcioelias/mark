<?php

namespace App\Console\Commands;

use App\Constants\LeadStatuses;
use App\Constants\PostbackEventType;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\Lead;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ConvertAll:schedule-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agenda envio de notificações para Leads sem nenhum agendamento.';

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
     * @return int
     */
    public function handle()
    {
        return $this->processScheduling();
    }

    public function getLeadsWithOutScheule() {
        return Lead::doesntHave('schedules')->get();
    }

    public function processScheduling() {
        try {
            $leads = $this->getLeadsWithOutScheule();

            foreach ($leads as $lead) {
                $this->analyseLead($lead);
            }
            return 0;
        } catch (Exception $e) {
            if (config('app.debug', false)) {
                Log::debug($e);
            }
            return 1;
        }
    }

    public function analyseLead(Lead $lead) {
        $funnel = $lead->product->funnel;
        if ($funnel) {
            $step = FunnelStep::where('funnel_id', $funnel->id)->where('postback_event_type_id', $this->getMappedLeadPostbackStatus($lead))->first();
            foreach ($step->actions as $action) {
               Log::debug($action); 
            }
        }
    }

    public function getMappedLeadPostbackStatus(Lead $lead) {
        switch ($lead->lead_status_id) {
            case LeadStatuses::APPROVED:
                return PostbackEventType::APPROVED;
                break;

                case LeadStatuses::APPROVED:
                    return PostbackEventType::APPROVED;
                    break;

                case LeadStatuses::BILLET_PRINTED:
                    return PostbackEventType::BILLET_PRINTED;
                    break;

                case LeadStatuses::CANCELED:
                    return PostbackEventType::CANCELED;
                    break;
                
                case LeadStatuses::REFUNDED:
                    return PostbackEventType::REFUNDED;
                    break;

                case LeadStatuses::DISPUTE:
                    return PostbackEventType::DISPUTE;
                    break;
            
            default:
                return null;
                break;
        }
    }

    public function leadApplyToAction(Lead $lead, FunnelStepAction $action) {
        $daysAfterCreated = Carbon::now()->diff($lead->created_at)->days;
        
    }
}

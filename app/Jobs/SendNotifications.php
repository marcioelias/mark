<?php

namespace App\Jobs;

use App\Constants\ActionTypes;
use App\Constants\TransactionTypes;
use App\Events\NotificationSent;
use App\Mail\ActionSendEmail;
use App\Models\User;
use App\Models\User\Funnel;
use App\Models\User\FunnelStepAction;
use App\Models\User\FunnelStepLead;
use App\Models\User\Lead;
use App\Models\User\Schedule;
use App\Models\User\SentMessage;
use App\Models\User\SmsUserTransaction;
use App\Models\Variable;
use App\SMS\GatewaySms;
use App\SMS\SmsFactory;
use App\Whatsapp\WhatsappIntegration;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\SqsQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class SendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schedule;
    private $notificationData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->processJob();
        } catch (Exception $e) {
            Log::emergency($e);
        }
    }

    private function processJob() {
        Log::info('process job');
        $this->notificationData = $this->getNotificationData();
        switch ($this->schedule->action->action_type_id) {
            case ActionTypes::EMAIL:
                $this->sendEmail();
                break;

            case ActionTypes::SMS:
                $this->sendSMS();
                break;

            case ActionTypes::WHATSAPP;
                $this->sendWhatsapp();
                break;

            case ActionTypes::REMARKETING:
                $this->remarketing($this->schedule);
                break;
        }

        event(new NotificationSent($this->schedule, $this->notificationData));
    }

    private function sendSMS() {
        $msg = $this->notificationData;
        $to = $this->schedule->lead->customer->customer_phone_number;
        $result = SmsFactory::getSmsGateway($msg, $to)->send();

        $this->logMessageSent($result['returnMessage'], $result['successful']);
    }

    private function sendWhatsapp() {
        $msg = $this->notificationData;
        $to = $this->schedule->lead->customer->customer_phone_number;
        $wppInstance = $this->schedule->lead->product->whatsappInstance;
        if ($wppInstance) {
            $wppIntegration = new WhatsappIntegration($wppInstance);
            $result = $wppIntegration->sendText($msg, $to);
            if ($result['successful']) {
                /* se configurado para enviar o arquivo do boleto, dispara uma msg com o mesmo */
                try {
                    if ($this->schedule->action->action_data['options']['send_billet'] ?? false) {
                        $variables = $this->getVariables();
                        if ($variables['url_boleto']) {
                            $wppIntegration->sendFile($variables['url_boleto'], $to);
                        }
                    }
                    $this->logMessageSent($result['returnMessage'], true);
                } catch (Exception $e) {
                    $this->logMessageSent($e->getMessage(), false);
                    Log::emergency($e->getMessage());
                }
            } else {
                $this->logMessageSent($result['returnMessage'], false);
            }
        } else {
            $this->logMessageSent('Não há instância de Whatsapp para este Produto', false);
        }
    }

    private function sendEmail() {
        try {
            $from = $this->schedule->user->name;
            $replyTo = $this->schedule->user->email;
            $to = $this->schedule->lead->customer->customer_email;
            $subject = $this->getMailSubject();
            $msg = $this->notificationData;
            Mail::to($to)->send(new ActionSendEmail($from, $replyTo, $subject, $msg));

            $this->logMessageSent('E-mail enviado com sucesso.', true);
        } catch (Exception $e) {
            $this->logMessageSent($e->getMessage(), false);
        }
    }

    private function getNotificationData() {
        return $this->replaceVariables($this->schedule->action->action_data['data']);
    }

    private function getMailSubject() {
        return $this->replaceVariables(Arr::get($this->schedule->action->action_data, 'options.subject')) ?? '';
    }

    private function getVariables() {
        $result['nome_cliente'] = $this->schedule->lead->customer->customer_name;
        $result['telefone_cliente'] = $this->schedule->lead->customer->customer_phone_number;
        $result['email_cliente'] = $this->schedule->lead->customer->customer_email;
        $result['url_boleto'] = $this->schedule->lead->billet_url;
        $result['linha_digitavel'] = $this->schedule->lead->billet_barcode;

        return $result;
    }

    private function replaceVariables(string $message) {
        $regex = '~\{([\s\w]*)\}~';
        $variables = $this->getVariables();
        return preg_replace_callback($regex, function($match) use ($variables) {
            return $variables[trim($match[1])];
        }, $message);
    }

    private function remarketing(Schedule $schedule) {
        $funnelStepLead = FunnelStepLead::where('lead_id', $schedule->lead_id)
                                    ->where('funnel_step_id', $schedule->funnel_step_id)
                                    ->first();

        if ($funnelStepLead) {
            $funnelStepLead->active = 0;
            $funnelStepLead->save();
        }

        $funnel = Funnel::find($schedule->funnelStepAction->action_data['data']);
        if ($funnel) {
            $funnelStep = $funnel->steps()->first();
            if ($funnelStep) {
                $funnelStepLead = FunnelStepLead::create([
                    'user_id' => $schedule->user_id,
                    'funnel_step_id' => $funnelStep->id,
                    'lead_id' => $schedule->lead_id
                ]);

                $funnelStepLead->save();

                $this->scheduleAction($schedule, $funnelStepLead);
            }
        }
    }
    /**
     * Create a schedule task to handle the action
     *
     * @param Postback $postback
     * @param FunnelStepLead $funnelStepLead
     * @return void
     */
    private function scheduleAction(Schedule $schedule, FunnelStepLead $funnelStepLead) {
        $action = $this->getAction($funnelStepLead);
        $schedule = new Schedule([
            'user_id' => $schedule->user_id,
            'lead_id' => $schedule->lead_id,
            'funnel_step_id' => $funnelStepLead->funnelStep->id,
            'funnel_step_action_id' => $action->id,
            'start_at' => $this->getScheduleStartAt($action, $funnelStepLead),
            'start_period' => $this->getSheduleStartPeriod($action),
            'end_period' => $this->getSheduleEndPeriod($action),
            'delay_before_start' => $this->getDelayBeforeStart($action),
        ]);

        $schedule->save();
    }

    /**
     * Get start datetime of an Action based on the number of the days that the action
     * is configured to run after the ingress event of the step
     *
     * @param FunnelStepAction $funnelStepAction
     * @param FunnelStepLead $funnelStepLead
     * @return DateTime
     */
    private function getScheduleStartAt(FunnelStepAction $funnelStepAction, FunnelStepLead $funnelStepLead): DateTime
    {
        return Carbon::parse($funnelStepLead->created_at)->startOfDay()
                                                         ->addDays($funnelStepAction->action_data['options']['days_after'] ?? 0);
    }

    /**
     * Get minimum time of the day to start running a action, in format HH:nn
     *
     * @param FunnelStepAction $funnelStepAction
     * @return string
     */
    private function getSheduleStartPeriod(FunnelStepAction $funnelStepAction): string
    {
        return Carbon::parse($funnelStepAction->action_data['options']['start_time'] ?? '00:00')->toTimeString();
    }

    /**
     * Get maximum time of the day to start running a action, in format HH:nn
     *
     * @param FunnelStepAction $funnelStepAction
     * @return string
     */
    private function getSheduleEndPeriod(FunnelStepAction $funnelStepAction): string
    {
        return Carbon::parse($funnelStepAction->action_data['options']['end_time'] ?? '23:59')->toTimeString();
    }

    /**
     * Get te number of minutes that the action must dalay his execution
     *
     * @param FunnelStepAction $funnelStepAction
     * @return integer
     */
    private function getDelayBeforeStart(FunnelStepAction $funnelStepAction): int
    {
        return $funnelStepAction->action_data['options']['delay_minutes'] ?? 0;
    }


    /**
     * Get the first action to be executed at current step
     *
     * @param FunnelStepLead $funnelStepLead
     * @return FunnelStepAction
     */
    private function getAction(FunnelStepLead $funnelStepLead): FunnelStepAction
    {
        return $funnelStepLead->funnelStep->actions()->orderBy('action_sequence', 'asc')->first();
    }

    private function logMessageSent(string $returnData, bool $success) {
        SentMessage::create([
            'user_id' => $this->schedule->user_id,
            'lead_id' => $this->schedule->lead_id,
            'funnel_step_action_id' => $this->schedule->funnel_step_action_id,
            'message_data' => $this->notificationData,
            'return_data' => $returnData,
            'is_successful' => $success
        ]);
    }
}

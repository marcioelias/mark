<?php

namespace App\Jobs;

use App\Constants\ActionTypes;
use App\Constants\MarketingActionStatuses;
use App\Constants\TransactionTypes;
use App\Events\NotificationSent;
use App\Mail\ActionSendEmail;
use App\Models\User;
use App\Models\User\Customer;
use App\Models\User\Funnel;
use App\Models\User\FunnelStepAction;
use App\Models\User\FunnelStepLead;
use App\Models\User\Lead;
use App\Models\User\MarketingAction;
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

class SendMarketingAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;
    private $marketingAction;
    private $result;
    private $resultMsg;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MarketingAction $marketingAction, Customer $customer)
    {
        $this->customer = $customer;
        $this->marketingAction = $marketingAction;
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
        $this->notificationData = $this->getNotificationData();
        switch ($this->marketingAction->action_type_id) {
            case ActionTypes::EMAIL:
                $result = $this->sendEmail();
                break;

            case ActionTypes::SMS:
                $result = $this->sendSMS();
                break;

            case ActionTypes::WHATSAPP;
                $result = $this->sendWhatsapp();
                break;
        }

        $customerMarketingAction = $this->marketingAction->customers->find($this->customer->id);
        $customerMarketingAction->pivot->finished_at = now();
        $customerMarketingAction->pivot->result_ok = $result['successful'];
        $customerMarketingAction->pivot->result_message = $result['returnMessage'];
        $customerMarketingAction->pivot->save();

        $this->changeMarketingActionStatus();
    }

    private function sendSMS() {
        $msg = $this->notificationData;
        $to = $this->customer->customer_phone_number;
        return SmsFactory::getSmsGateway($msg, $to)->send();
    }

    private function sendWhatsapp() {
        $msg = $this->notificationData;
        $to = $this->customer->customer_phone_number;
        $wppInstance = $this->marketingAction->product->whatsappInstance;
        if ($wppInstance) {
            $wppIntegration = new WhatsappIntegration($wppInstance);
            return $wppIntegration->sendText($msg, $to);
        }
    }

    private function sendEmail() {
        try {
            $from = $this->schedule->lead->product->email_from_name ?? $this->marketingAction->user->name;
            $replyTo = $this->schedule->lead->product->email ?? $this->marketingAction->user->email;
            $to = $this->customer->customer_email;
            $subject = $this->getMailSubject();
            $msg = $this->notificationData;
            $result = Mail::to($to)->send(new ActionSendEmail($from, $replyTo, $subject, $msg));
            if ($result) {
                return [
                    'returnMessage' => 'E-mail enviado.',
                    'successful' => true
                ];
            } else {
                return [
                    'returnMessage' => 'E-mail não pode ser enviado.',
                    'successful' => false
                ];
            }
        } catch (Exception $e) {
            return [
                'returnMessage' => 'Ocorreu um erro inesperado.',
                'successful' => false
            ];
        }
    }

    private function getNotificationData() {
        return $this->replaceVariables($this->marketingAction->action_message['data']);
    }

    private function getMailSubject() {
        return $this->replaceVariables(Arr::get($this->marketingAction->action_message, 'options.subject')) ?? '';
    }

    private function getVariables() {
        $result['nome_cliente'] = $this->customer->customer_name;
        $result['primeiro_nome'] = strtok($this->customer->customer_name, ' ');
        $result['telefone_cliente'] = $this->customer->customer_phone_number;
        $result['email_cliente'] = $this->customer->customer_email;
        $result['url_boleto'] = '';
        $result['linha_digitavel'] = '';

        return $result;
    }

    private function replaceVariables(string $message) {
        $regex = '~\{([\s\w]*)\}~';
        $variables = $this->getVariables();
        return preg_replace_callback($regex, function($match) use ($variables) {
            return $variables[trim($match[1])];
        }, $message);
    }

    private function changeMarketingActionStatus() {
        if (!$this->marketingAction->HasPendingActions()) {
            $this->marketingAction->marketing_action_status_id = MarketingActionStatuses::DONE;
            $this->marketingAction->save();
        }
    }
}

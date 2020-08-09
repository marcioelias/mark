<?php

namespace App\Jobs;

use App\Constants\ActionTypes;
use App\Events\NotificationSent;
use App\Mail\ActionSendEmail;
use App\Models\User\FunnelStepAction;
use App\Models\User\Lead;
use App\Models\User\Schedule;
use App\Models\Variable;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class SendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schedule;

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
        Redis::throttle('sendNotification')->allow(1)->every(10)->block(10)->then(function () {
            try {
                $this->send();
            } catch (Exception $e) {
                Log::emergency($e);
            }
        }, function () {
            return $this->release(5);
        });
    }

    private function send() {
        switch ($this->schedule->action->action_type_id) {
            case ActionTypes::EMAIL:
                $this->sendEmail();
                break;

            case ActionTypes::SMS:
                $this->sendSMS();
        }

        event(new NotificationSent($this->schedule));
    }

    private function sendSMS() {
        $msg = $this->getNotificationData();
        Http::post('https://api.dev.test/api/sms', ['data' => $msg]);
    }

    private function sendEmail() {
        $from = $this->schedule->user->email;
        $to = $this->schedule->lead->customer->customer_email;
        $subject = $this->getMailSubject();
        $msg = $this->getNotificationData();
        Mail::to($to)->send(new ActionSendEmail($from, $subject, $msg));

        /* Mail::send([], [], function ($message) use ($from, $to, $subject, $msg) {
            $message->to($to)
                    ->subject($subject)
                    ->from($from)
                    ->setBody($msg, 'text/html');
        }); */
    }

    private function getNotificationData() {
        return $this->replaceVariables(json_decode($this->schedule->action->action_data, true)['data']);
    }

    private function getMailSubject() {
        return $this->replaceVariables(Arr::get(json_decode($this->schedule->action->action_data, true), 'options.subject')) ?? '';
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
}

<?php

namespace App\Http\Controllers;

use App\Constants\ActionTypes;
use App\Models\User\FunnelStepAction;
use App\Models\User\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FunnelStepActionController extends Controller
{
    public function getActionMessage(FunnelStepAction $funnelStepAction, Lead $lead) {
        if ($funnelStepAction->action_type_id === ActionTypes::EMAIL) {
            return response()->json([
                'subject' => $this->getMailSubject($funnelStepAction, $lead),
                'message' => $this->getNotificationData($funnelStepAction, $lead)
            ]);
        } else {
            return response()->json([
                'subject' => '',
                'message' => $this->getNotificationData($funnelStepAction, $lead)
            ]);
        }
    }

    private function getVariables(Lead $lead) {
        $result['nome_cliente'] = $lead->customer->customer_name;
        $result['primeiro_nome'] = strtok($lead->customer->customer_name, ' ');
        $result['telefone_cliente'] = $lead->customer->customer_phone_number;
        $result['email_cliente'] = $lead->customer->customer_email;
        $result['url_boleto'] = $lead->billet_url;
        $result['linha_digitavel'] = $lead->billet_barcode;

        return $result;
    }

    private function getNotificationData(FunnelStepAction $funnelStepAction, Lead $lead) {
        return $this->replaceVariables(json_decode($funnelStepAction->action_data, true)['data'], $lead);
    }

    private function getMailSubject(FunnelStepAction $funnelStepAction, Lead $lead) {
        return $this->replaceVariables(Arr::get(json_decode($funnelStepAction->action_data, true), 'options.subject'), $lead) ?? '';
    }

    private function replaceVariables(string $message, Lead $lead) {
        $regex = '~\{([\s\w]*)\}~';
        $variables = $this->getVariables($lead);
        return preg_replace_callback($regex, function($match) use ($variables) {
            return $variables[trim($match[1])];
        }, $message);
    }

}

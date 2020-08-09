<?php

namespace App\Http\Controllers;

use App\Events\NewLeadCreated;
use App\Integrations\IntegrationFactory;
use App\Models\User\PlataformConfig;
use App\Models\User\Postback;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookCallController extends Controller
{

    private $integration;

    public function receiveUserWebhook(Request $request, PlataformConfig $plataformConfig) {
        $this->integration = IntegrationFactory::getIntegration($request, $plataformConfig);

        return $this->proccessReceivedCall();
    }

    public function proccessReceivedCall() {
        DB::beginTransaction();
        try {
            $customer = $this->integration->getCustomer();
            $customer->save();

            $postback = $this->integration->getPostback();
            $lead = $this->integration->getLead();
            $lead->postbacks()->save($postback);

            DB::commit();

            /* dispatch event for process tag rules */
            event(new NewLeadCreated($lead));

            return $this->handleTransactionOk($postback);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    private function handleException(Exception $exception) {
        if (env('APP_DEBUG', false)) {
            Log::emergency($exception);
        } else {
            Log::emergency($exception->getMessage());
        }


        switch ($exception->getCode()) {
            case 401:
                $httpCode = 401;
                break;

            default:
                $httpCode = 500;
                break;
        }

        return response()->json([
                    'status' => 'error',
                    'data' => [
                        'error_code' => $exception->getCode(),
                        'error_message' => $exception->getMessage()
                    ]
                ], $httpCode);
    }

    private function handleTransactionOk(Postback $postback) {
        return response()->json([
            'status' => 'success',
            'data' => [
                'postback_id' => $postback->id
            ]
        ], 200);
    }
}

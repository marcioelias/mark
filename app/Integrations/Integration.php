<?php

namespace App\Integrations;

use App\Constants\FeatureTypes;
use App\Constants\LeadStatuses;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;
use App\Models\User;
use App\Models\User\Customer;
use App\Models\User\FunnelStep;
use App\Models\User\Lead;
use App\Models\User\PlataformConfig;
use App\Models\User\Postback;
use App\Models\User\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class Integration {

    protected $request;
    protected $plataformConfig;
    protected $mappedFields;
    protected User $user;


    public $token;
    public $transactionCode;
    public $customerName;
    public $customerEmail;
    public $customerPhone;
    public $productCode;
    public $billetUrl;
    public $billetBarcode;
    public $value;
    public $paymentType;
    public $paidAt;
    public $eventType;
    public $product;
    public $customer;

    public function __construct(Request $request, PlataformConfig $plataformConfig) {
        $this->request = $request;
        $this->plataformConfig = $plataformConfig;
        $this->user = $this->getUser();
        $this->mappedFields = $this->setMappedRequestFields();
        $this->getDataFromRequest();
    }

    protected function setMappedRequestFields() {
        return [];
    }

    protected function postbackTokenValidate() {
        if ((string) $this->plataformConfig->plataform_key !== (string) Arr::get($this->request, $this->mappedFields[PostbackField::token()->getField()])) {
            throw new Exception('Não autorizado', 401);
        }
    }

    protected function getDataFromRequest() {
        try {
            $this->postbackTokenValidate();

            foreach (PostbackField::values() as $field) {
                $var = $field->getField();
                $map = $this->mappedFields[$field->getField()];
                if (is_array($map)) {
                    foreach ($map as $mapped) {
                        $this->$var .= Arr::get($this->request->all(), $mapped, '');
                    }
                } else {
                    $this->$var = Arr::get($this->request->all(), $map, '');
                }
            }

            $this->product = $this->getProduct();
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getProduct() {
        Log::info('getProduct()');
        Log::info('plataform_code => '.$this->productCode);
        Log::info('plataform_config_id => '.$this->plataformConfig->id );
        $product = Product::where('plataform_code', $this->productCode)
                        ->where('plataform_config_id', $this->plataformConfig->id)
                        ->first();

        Log::info('return => '.$product);
        if ($product) {
            return $product;
        } else {
            throw new Exception('Produto não encontrado', 404);
        }
    }

    public function getCustomer() {
        try {
            $this->customer = Customer::updateOrCreate(
                [
                    'user_id' => $this->plataformConfig->user_id,
                    'customer_email' => $this->customerEmail
                ],
                [
                    'customer_name' => $this->customerName,
                    'customer_phone_number' => $this->customerPhone,
                ]
            );

            return $this->customer;

        } catch (Exception $e) {
            Log::emergency($e);
        }
    }

    public function getMappedEventType() {
        //
    }

    public function getMappedPaymentType() {
        //
    }

    public function getPlataformConfig() {
        return $this->plataformConfig;
    }

    public function getUser() {
        return User::find($this->getPlataformConfig()->user_id);
    }

    private function userActive(User $user): bool
    {
        $expireAt = Carbon::parse($user->activated_at)->addDays($user->plan->plan_cycle_days ?? 30);
        if (Carbon::now()->gt($expireAt)) {
            $user->active = false;
            $user->save();
        }

        return $user->active;
    }

    public function getPayload() {
        return json_encode($this->request->all());
    }

    public function getPaidAt() {
        return $this->paidAt ? Carbon::parse($this->paidAt) : null;
    }

    public function getLeadStatus() {
        switch ($this->getMappedEventType()) {
            case PostbackEventType::BILLET_PRINTED:
                return LeadStatuses::BILLET_PRINTED;
                break;
            case PostbackEventType::DISPUTE:
                return LeadStatuses::DISPUTE;
                break;
            case PostbackEventType::APPROVED:
                return LeadStatuses::APPROVED;
                break;
            case PostbackEventType::CANCELED:
                return LeadStatuses::CANCELED;
                break;
            case PostbackEventType::REFUNDED:
                return LeadStatuses::REFUNDED;
                break;
        }
    }

    public function getPostback() {
        return Postback::firstOrCreate(
            [
                'user_id' => $this->getPlataformConfig()->user_id,
                'product_id' => $this->product->id,
                'customer_id' => $this->customer->id,
                'postback_event_type_id' => $this->getMappedEventType(),
                'transaction_code' => $this->transactionCode,
                'visible' => ($this->userActive($this->user) && $this->user->postbacksAvailable())
            ],
            [
                'payload' => $this->getPayload()
            ]
        );
    }

    public function getLead() {
        return Lead::updateOrCreate(
            [
                'user_id' => $this->getPlataformConfig()->user_id,
                'product_id' => $this->product->id,
                'customer_id' => $this->customer->id,
                'transaction_code' => $this->transactionCode
            ],
            [
                'payment_type_id' => $this->getMappedPaymentType(),
                'billet_url' => $this->billetUrl,
                'billet_barcode' => $this->billetBarcode,
                'value' => $this->value,
                'paid_at' => $this->getPaidAt(),
                'lead_status_id' => $this->getLeadStatus(),
                'visible' => $this->user->active && $this->user->leadsAvailable()
            ]
        );
    }

    public function getSalesFunnelFirstStepId() {
        $funnelStep = FunnelStep::select('funnel_steps.*')
                        ->join('funnels', 'funnels.id', 'funnel_steps.funnel_id')
                        ->where('funnels.is_sale_funnel', true)
                        ->where('funnel_steps.postback_event_type_id', PostbackEventType::BILLET_PRINTED)
                        ->first();

        return $funnelStep->id;
    }

    public function validateFeature(FeatureTypes $featureType) {
        $feature = $this->user->plan->feature->firstWhere('id', $featureType)->pivot;
        return $feature->enabled && ($feature->limit == 0 || ($feature->limit > $this->getUserFeatureUsage($featureType)));
    }

    private function getUserFeatureUsage(FeatureTypes $featureType) {
        return 0; //obter o total usado pelo usuário no mês atual
    }
}

<?php

namespace App\Integrations;

use App\Constants\LeadStatus;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;
use App\Models\User\Customer;
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


    public $token;
    public $transactionCode;
    public $customerName;
    public $customerEmail;
    public $customerPhone;
    public $productCode;
    public $billetUrl;
    public $billetBarcode;
    public $value;
    public $paidAt;
    public $eventType;
    public $product;
    public $customer;

    public function __construct(Request $request, PlataformConfig $plataformConfig) {
        $this->request = $request;
        $this->plataformConfig = $plataformConfig;
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
        $product = Product::where('plataform_code', $this->productCode)->first();
        if ($product) {
            return $product;
        } else {
            throw new Exception('Produto não encontrado', 500);
        }
    }

    public function getCustomer() {
        $this->customer = Customer::updateOrCreate(
            [
                'user_id' => $this->plataformConfig->user_id,
                'customer_email' => $this->customerEmail
            ],
            [
                'customer_name' => $this->customerName,
                'customer_phone_number' => $this->customerPhone
            ]
        );

        return $this->customer;
    }

    public function getMappedEventType() {
        //
    }

    public function getPlataformConfig() {
        return $this->plataformConfig;
    }

    public function getPayload() {
        return json_encode($this->request->all());
    }

    public function getPaidAt() {
        return $this->paidAt ? Carbon::parse($this->paidAt) : null;
    }

    public function getLeadStatus() {
        switch ($this->getMappedEventType()) {
            case PostbackEventType::ABANDONO_CHECKOUT:
                return LeadStatus::ABANDONO;
                break;
            case PostbackEventType::IMPRESSAO_BOLETO:
                return LeadStatus::EM_ABERTO;
                break;
            case PostbackEventType::BOLETO_VENCENDO:
                return LeadStatus::VENCENDO;
                break;
            case PostbackEventType::BOLETO_VENCIDO:
                return LeadStatus::VENCIDO;
                break;
            case PostbackEventType::COMPRA_FINALIZADA:
                return LeadStatus::FINALIZADO;
                break;
            case PostbackEventType::COMPRA_CANCELADA:
                return LeadStatus::CANCELADO;
                break;
        }
    }

    public function getPostback() {
        return new Postback([
            'user_id' => $this->getPlataformConfig()->user_id,
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'event_type' => $this->getMappedEventType(),
            'payload' => $this->getPayload()
        ]);
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
                'billet_url' => $this->billetUrl,
                'billet_barcode' => $this->billetBarcode,
                'value' => $this->value,
                'paid_at' => $this->getPaidAt(),
                'status' => $this->getLeadStatus()
            ]
        );
    }
}

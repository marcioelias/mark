<?php

namespace App\Integrations;

use App\Constants\PaymentTypes;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;

class TictoIntegration extends Integration {
    protected function setMappedRequestFields() {
        return [
            PostbackField::token()->getField()           => 'token',
            PostbackField::transactionCode()->getField() => 'id_transaction',
            PostbackField::productCode()->getField()     => 'id_prod',
            PostbackField::customerName()->getField()    => 'name_customer',
            PostbackField::customerEmail()->getField()   => 'email_customer',
            PostbackField::customerPhone()->getField()   => ['phone_local_code_customer', 'phone_number_customer'],
            PostbackField::billetUrl()->getField()       => 'billet_url',
            PostbackField::billetBarcode()->getField()   => 'billet_barcode',
            PostbackField::eventType()->getField()       => 'status',
            PostbackField::paidAt()->getField()          => 'confirmation_purchase_date',
            PostbackField::value()->getField()           => 'transaction_price',
            PostbackField::paymentType()->getField()     => 'payment_type',
        ];
    }

    public function getMappedEventType() {
        switch ($this->eventType) {
            case 'billet_printed':
                return PostbackEventType::BILLET_PRINTED;
                break;

            case 'paid':
                return PostbackEventType::APPROVED;
                break;

            case 'canceled':
                return PostbackEventType::CANCELED;
                break;

            case 'refunded':
                return PostbackEventType::REFUNDED;
                break;

            case 'chargeback':
                return PostbackEventType::DISPUTE;
                break;

            case 'abandoned_cart':
                return PostbackEventType::ABANDONED_CART;
                break;
        }
    }

    public function getPayload() {
        return $this->request->json;
    }

    public function getMappedPaymentType() {
        switch ($this->paymentType) {
            case 'credit_card':
                return PaymentTypes::CARTAO_CREDITO;
                break;

            case 'billet':
                return PaymentTypes::BOLETO_BANCARIO;
                break;

            default:
                return PaymentTypes::OUTROS;
                break;
        }
    }
}

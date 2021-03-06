<?php

namespace App\Integrations;

use App\Constants\PaymentTypes;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;

class HotmartIntegration extends Integration {
    protected function setMappedRequestFields() {
        return [
            PostbackField::token()->getField()           => 'hottok',
            PostbackField::transactionCode()->getField() => 'transaction',
            PostbackField::productCode()->getField()     => 'prod',
            PostbackField::customerName()->getField()    => 'name',
            PostbackField::customerEmail()->getField()   => 'email',
            PostbackField::customerPhone()->getField()   => ['phone_checkout_local_code', 'phone_checkout_number'],
            PostbackField::billetUrl()->getField()       => 'billet_url',
            PostbackField::billetBarcode()->getField()   => 'billet_barcode',
            PostbackField::eventType()->getField()       => 'status',
            PostbackField::paidAt()->getField()          => 'confirmation_purchase_date',
            PostbackField::value()->getField()           => 'full_price',
            PostbackField::paymentType()->getField()     => 'payment_type'
        ];
    }

    public function getMappedEventType() {
        switch ($this->eventType) {
            case 'approved':
                return PostbackEventType::APPROVED;
                break;

            case 'canceled':
                return PostbackEventType::ABANDONED_CART;
                break;

            case 'billet_printed':
            case 'wayting_payment':
                return PostbackEventType::BILLET_PRINTED;
                break;

            case 'refunded':
                return PostbackEventType::REFUNDED;
                break;

            case 'dispute':
                return PostbackEventType::DISPUTE;
                break;
        }
    }

    public function getMappedPaymentType() {
        switch ($this->paymentType) {
            case 'billet':
                return PaymentTypes::BOLETO_BANCARIO;
                break;

            case 'credit_card':
                return PaymentTypes::CARTAO_CREDITO;
                break;

            case 'debit_card':
                return PaymentTypes::CARTAO_DEBIDO;
                break;

            case 'paypal':
                return PaymentTypes::PAYPAL;
                break;

            default:
                return PaymentTypes::OUTROS;
                break;
        }
    }
}

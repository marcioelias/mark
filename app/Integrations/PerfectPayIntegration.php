<?php

namespace App\Integrations;

use App\Constants\PaymentTypes;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;

class PerfectPayIntegration extends Integration {
    protected function setMappedRequestFields() {
        return [
            PostbackField::token()->getField()           => 'token',
            PostbackField::transactionCode()->getField() => 'code',
            PostbackField::productCode()->getField()     => 'product.code',
            PostbackField::customerName()->getField()    => 'customer.full_name',
            PostbackField::customerEmail()->getField()   => 'customer.email',
            PostbackField::customerPhone()->getField()   => ['customer.phone_area_code', 'customer.phone_number'],
            PostbackField::billetUrl()->getField()       => 'billet_url',
            PostbackField::billetBarcode()->getField()   => 'billet_number',
            PostbackField::eventType()->getField()       => 'sale_status_enum',
            PostbackField::paidAt()->getField()          => 'date_approved',
            PostbackField::value()->getField()           => 'sale_amount',
            PostbackField::paymentType()->getField()     => 'payment_type_enum'
        ];
    }

    public function getMappedEventType() {
        switch ($this->eventType) {
            case '1':
                return PostbackEventType::BILLET_PRINTED;
                break;

            case '2':
                return PostbackEventType::APPROVED;
                break;

            case '4':
                return PostbackEventType::DISPUTE;
                break;

            case '6':
                return PostbackEventType::CANCELED;
                break;

            case '7':
                return PostbackEventType::REFUNDED;
                break;
        }
    }

    public function getMappedPaymentType() {
        switch ($this->paymentType) {
            case 1:
            case 4:
            case 6:
                return PaymentTypes::CARTAO_CREDITO;
                break;

            case 2:
                return PaymentTypes::BOLETO_BANCARIO;
                break;

            case 3:
                return PaymentTypes::PAYPAL;
                break;

            default:
                return PaymentTypes::OUTROS;
                break;
        }
    }
}

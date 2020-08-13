<?php

namespace App\Integrations;

use App\Constants\PaymentTypes;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;

class EduzzIntegration extends Integration {
    protected function setMappedRequestFields() {
        return [
            PostbackField::token()->getField()           => 'api_key',
            PostbackField::transactionCode()->getField() => 'trans_cod',
            PostbackField::productCode()->getField()     => 'product_cod',
            PostbackField::customerName()->getField()    => 'cus_name',
            PostbackField::customerEmail()->getField()   => 'cus_email',
            PostbackField::customerPhone()->getField()   => 'cus_cel',
            PostbackField::billetUrl()->getField()       => 'billet_url',
            PostbackField::billetBarcode()->getField()   => 'trans_barcode',
            PostbackField::eventType()->getField()       => 'trans_status',
            PostbackField::paidAt()->getField()          => ['trans_paiddate', 'trans_paidtime'],
            PostbackField::value()->getField()           => 'trans_value',
            PostbackField::paymentType()->getField()     => 'trans_paymentmethod'
        ];
    }

    public function getMappedEventType() {
        switch ($this->eventType) {
            case '1':
                return PostbackEventType::IMPRESSAO_BOLETO;
                break;

            case '3':
                return PostbackEventType::COMPRA_FINALIZADA;
                break;

            case '4':
                return PostbackEventType::COMPRA_CANCELADA;
                break;
        }
    }

    public function getMappedPaymentType() {
        switch ($this->paymentType) {
            case 1:
                return PaymentTypes::BOLETO_BANCARIO;
                break;

            case 9:
            case 25:
                return PaymentTypes::PAYPAL;
                break;

            case 13:
            case 14:
            case 15:
            case 16:
            case 21:
            case 23:
            case 24:
            case 27:
                return PaymentTypes::CARTAO_CREDITO;
                break;

            case 17:
            case 18:
            case 19:
            case 22:
                return PaymentTypes::CARTAO_DEBIDO;
                break;

            default:
                return PaymentTypes::OUTROS;
                break;
        }
    }
}

<?php

namespace App\Integrations;

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
        ];
    }

    public function getMappedEventType() {
        switch ($this->eventType) {
            case 'billet_printed':
                return PostbackEventType::IMPRESSAO_BOLETO;
                break;

            case 'wayting_payment':
                return PostbackEventType::IMPRESSAO_BOLETO;
                break;

            case 'approved':
                return PostbackEventType::COMPRA_FINALIZADA;
                break;

            case 'canceled':
                return PostbackEventType::COMPRA_CANCELADA;
                break;

            case 'completed':
                return PostbackEventType::COMPRA_FINALIZADA;
                break;
        }
    }
}

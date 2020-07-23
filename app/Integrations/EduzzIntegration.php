<?php

namespace App\Integrations;

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
}

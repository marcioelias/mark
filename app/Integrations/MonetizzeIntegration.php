<?php

namespace App\Integrations;

use App\Constants\PaymentTypes;
use App\Constants\PostbackEventType;
use App\Enums\PostbackField;

class MonetizzeIntegration extends Integration {
    protected function setMappedRequestFields() {
        return [
            PostbackField::token()->getField()           => 'chave_unica',
            PostbackField::transactionCode()->getField() => 'venda.codigo',
            PostbackField::productCode()->getField()     => 'produto.codigo',
            PostbackField::customerName()->getField()    => 'comprador.nome',
            PostbackField::customerEmail()->getField()   => 'comprador.email',
            PostbackField::customerPhone()->getField()   => 'comprador.telefone',
            PostbackField::billetUrl()->getField()       => 'venda.linkBoleto',
            PostbackField::billetBarcode()->getField()   => 'venda.linha_digitavel',
            PostbackField::eventType()->getField()       => 'tipoEvento.codigo',
            PostbackField::paidAt()->getField()          => 'venda.dataFinalizada',
            PostbackField::value()->getField()           => 'venda.valor',
            PostbackField::paymentType()->getField()     => 'venda.formaPagamento',
        ];
    }

    public function getMappedEventType() {
        switch ($this->eventType) {
            case '1':
                return PostbackEventType::IMPRESSAO_BOLETO;
                break;

            case '2':
                return PostbackEventType::COMPRA_FINALIZADA;
                break;

            case '3':
                return PostbackEventType::COMPRA_CANCELADA;
                break;

            case '6':
                return PostbackEventType::COMPRA_FINALIZADA;
                break;

            case '7':
                return PostbackEventType::ABANDONO_CHECKOUT;
                break;
        }
    }

    public function getPayload() {
        return $this->request->json;
    }

    public function getMappedPaymentType() {
        switch ($this->paymentType) {
            case 'Cartão de crédito':
                return PaymentTypes::CARTAO_CREDITO;
                break;

            case 'Débito online':
                return PaymentTypes::CARTAO_DEBIDO;
                break;

            case 'Cartão de crédito':
                return PaymentTypes::BOLETO_BANCARIO;
                break;

            default:
                return PaymentTypes::OUTROS;
                break;
        }
    }
}

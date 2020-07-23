<?php

namespace App\Enums;

use Exception;

class PostbackEvent {
    private const ABANDONO          = 'abandonou';  /* cliente abandonou o checkout sem concluir a compra */
    private const BOLETO_IMPRESSO   = 'imprimiu';   /* cliente efetuou a impressão do boleto */
    private const BOLETO_VENCENDO   = 'vencendo';   /* boleto impresso está próximo do vencimento (dia anterior ao vencimento ou dia dovencimento) */
    private const BOLETO_VENCIDO    = 'venceu';     /* boleto venceu (dia posterior a data de vencimento, continua em aberto) */
    private const COMPRA_FINALIZADA = 'pagou';      /* cliente finalizou a compra, efetuou pagamento */
    private const COMPRA_CANCELADA  = 'cancelou';   /* compra foi cancelada */

    private static $values = null;

    private $event;

    private $displayValue;

    public function __construct(string $event, string $displayValue = null) {
        $this->event = $event;
        $this->displayValue = $displayValue;
    }

    public static function fromStatus(string $event): PostbackEvent {
        foreach (self::values() as $postbackEvent) {
            if ($postbackEvent->getEvent() === $event) {
                return $postbackEvent;
            }
        }
        throw new Exception("Evento de postback desconhecido ($event).");
    }

    public static function fromDisplayValue(string $displayValue): PostbackEvent {
        foreach (self::values() as $postbackEvent) {
            if ($postbackEvent->getDisplayValue() === $displayValue) {
                return $postbackEvent;
            }
        }
        throw new Exception("Evento de postback desconhecido ($displayValue).");
    }

    public static function values(): array
    {
        if (is_null(self::$values)) {
            self::$values = [
                self::ABANDONO => new PostbackEvent(self::ABANDONO, 'Abandonou checkout'),
                self::BOLETO_IMPRESSO => new PostbackEvent(self::BOLETO_IMPRESSO, 'Imprimiu Boleto'),
                self::BOLETO_VENCENDO => new PostbackEvent(self::BOLETO_VENCENDO, 'Boleto Vencendo'),
                self::BOLETO_VENCIDO => new PostbackEvent(self::BOLETO_VENCIDO, 'Boleto Venceu'),
                self::COMPRA_FINALIZADA => new PostbackEvent(self::COMPRA_FINALIZADA, 'Compra Finalizada'),
                self::COMPRA_CANCELADA => new PostbackEvent(self::COMPRA_CANCELADA, 'Compra Cancelada'),
            ];
        }
        return self::$values;
    }

    public static function abandono(): PostbackEvent
    {
        return self::values()[self::ABANDONO];
    }

    public static function boletoImpresso(): PostbackEvent
    {
        return self::values()[self::BOLETO_IMPRESSO];
    }

    public static function boletoVencendo(): PostbackEvent
    {
        return self::values()[self::BOLETO_VENCENDO];
    }

    public static function boletoVencido(): PostbackEvent
    {
        return self::values()[self::BOLETO_VENCIDO];
    }

    public static function compraFinalizada(): PostbackEvent
    {
        return self::values()[self::COMPRA_FINALIZADA];
    }

    public static function compraCancelada(): PostbackEvent
    {
        return self::values()[self::COMPRA_CANCELADA];
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }

    public function __toString()
    {
        return (string) $this->event;
    }
}

<?php

namespace App\Enums;

use Exception;

class PostbackField {
    private const TOKEN             = 'token';
    private const TRANSACTION_CODE  = 'transactionCode';
    private const PRODUCT_CODE      = 'productCode';
    private const CUSTOMER_NAME     = 'customerName';
    private const CUSTOMER_EMAIL    = 'customerEmail';
    private const CUSTOMER_PHONE    = 'customerPhone';
    private const BILLET_URL        = 'billetUrl';
    private const BILLET_BARCODE    = 'billetBarcode';
    private const EVENT_TYPE        = 'eventType';
    private const PAID_AT           = 'paidAt';
    private const VALUE             = 'value';
    private const PAYMENT_TYPE      = 'paymentType';

    private static $values = null;

    private $field;
    private $displayValue;

    public function __construct(string $field, string $displayValue = null) {
        $this->field = $field;
        $this->displayValue = $displayValue;
    }

    public static function fromStatus(string $field): Postbackfield {
        foreach (self::values() as $postbackField) {
            if ($postbackField->getEvent() === $field) {
                return $postbackField;
            }
        }
        throw new Exception("Campo de postback desconhecido ($field).");
    }

    public static function fromDisplayValue(string $displayValue): PostbackField {
        foreach (self::values() as $postbackField) {
            if ($postbackField->getDisplayValue() === $displayValue) {
                return $postbackField;
            }
        }
        throw new Exception("Campo de postback desconhecido ($displayValue).");
    }

    public static function values(): array
    {
        if (is_null(self::$values)) {
            self::$values = [
                self::TOKEN           => new PostbackField(self::TOKEN, 'TOKEN'),
                self::TRANSACTION_CODE  => new PostbackField(self::TRANSACTION_CODE, 'TRANSACTION_CODE'),
                self::PRODUCT_CODE      => new PostbackField(self::PRODUCT_CODE, 'PRODUCT_CODE'),
                self::CUSTOMER_NAME     => new PostbackField(self::CUSTOMER_NAME, 'CUSTOMER_NAME'),
                self::CUSTOMER_EMAIL    => new PostbackField(self::CUSTOMER_EMAIL, 'CUSTOMER_EMAIL'),
                self::CUSTOMER_PHONE    => new PostbackField(self::CUSTOMER_PHONE, 'CUSTOMER_PHONE'),
                self::BILLET_URL        => new PostbackField(self::BILLET_URL, 'BILLET_URL'),
                self::BILLET_BARCODE    => new PostbackField(self::BILLET_BARCODE, 'BILLET_BARCODE'),
                self::EVENT_TYPE        => new PostbackField(self::EVENT_TYPE, 'EVENT_TYPE'),
                self::PAID_AT           => new PostbackField(self::PAID_AT, 'PAID_AT'),
                self::VALUE             => new PostbackField(self::VALUE, 'VALUE'),
                self::PAYMENT_TYPE      => new PostbackField(self::PAYMENT_TYPE, 'PAYMENT_TYPE')
            ];
        }
        return self::$values;
    }

    public static function token(): PostbackField
    {
        return self::values()[self::TOKEN];
    }

    public static function transactionCode(): PostbackField
    {
        return self::values()[self::TRANSACTION_CODE];
    }

    public static function productCode(): PostbackField
    {
        return self::values()[self::PRODUCT_CODE];
    }

    public static function customerName(): PostbackField
    {
        return self::values()[self::CUSTOMER_NAME];
    }

    public static function customerEmail(): PostbackField
    {
        return self::values()[self::CUSTOMER_EMAIL];
    }

    public static function customerPhone(): PostbackField
    {
        return self::values()[self::CUSTOMER_PHONE];
    }

    public static function billetUrl(): PostbackField
    {
        return self::values()[self::BILLET_URL];
    }

    public static function billetBarcode(): PostbackField
    {
        return self::values()[self::BILLET_BARCODE];
    }

    public static function eventType(): PostbackField
    {
        return self::values()[self::EVENT_TYPE];
    }

    public static function paidAt(): PostbackField
    {
        return self::values()[self::PAID_AT];
    }

    public static function value(): PostbackField
    {
        return self::values()[self::VALUE];
    }

    public static function paymentType(): PostbackField
    {
        return self::values()[self::PAYMENT_TYPE];
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }

    public function __toString()
    {
        return (string) $this->field;
    }
}

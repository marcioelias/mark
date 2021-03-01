<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class SmsStock extends Model
{
    protected $fillable = [
        'amount', 'move_in', 'sms_buy_id'
    ];

    public function smsBuy() {
        return $this->belongsTo(SmsBuy::class);
    }

    public function smsSale() {
        return $this->belongsTo(SmsSale::class);
    }
}

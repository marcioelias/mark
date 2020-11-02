<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class SmsBuy extends Model
{
    protected $fillable = [
        'amount', 'unitary_value'
    ];

    public function smsStock() {
        return $this->hasOne(SmsStock::class);
    }
}

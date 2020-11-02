<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $fillable = [
        'payment_type'
    ];
}

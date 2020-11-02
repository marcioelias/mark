<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class SmsPackage extends Model
{
    protected $fillable = [
        'sms_package_name',
        'sms_package_description',
        'sms_amount',
        'package_value',
        'active'
    ];
}

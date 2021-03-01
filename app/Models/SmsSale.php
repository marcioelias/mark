<?php

namespace App\Models;

use App\Models\User\SmsUserTransaction;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class SmsSale extends Model
{
    protected $fillable = [
        'user_id', 
        'sms_package_id',
        'sms_user_transaction_id'
    ];

    public function smsStock() {
        return $this->hasOne(SmsStock::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function smsPackage() {
        return $this->belongsTo(SmsPackage::class);
    }

    public function smsUserTransaction() {
        return $this->belongsTo(SmsUserTransaction::class);
    }
}

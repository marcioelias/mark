<?php

namespace App\Models\User;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone_number', 'customer_email'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function Postbacks() {
        return $this->hasMany(Postback::class);
    }
}

<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Customer extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'customer_name', 'customer_phone_number', 'customer_email'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function Postbacks() {
        return $this->hasMany(Postback::class);
    }

    public function marketingActions() {
        return $this->belongsToMany(MarketingAction::class);
    }
}

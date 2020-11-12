<?php

namespace App\Models\User;

use App\Traits\NullableMultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class CustomerStatus extends Model
{
    use NullableMultiTenantable;

    protected $fillable = [
        'id',
        'user_id',
        'customer_status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function customers() {
        return $this->hasMany(Customer::class);
    }
}

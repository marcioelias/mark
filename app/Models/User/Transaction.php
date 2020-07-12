<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Transaction extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'product_id', 'customer_id', 'transaction_code', 'billet_url', 'billet_barcode', 'transaction_payload', 'user_custom_mapping'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}

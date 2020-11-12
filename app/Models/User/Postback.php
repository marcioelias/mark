<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use App\Traits\VisibleRecords;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Postback extends Model
{
    use MultiTenantable, VisibleRecords;

    protected $fillable = [
        'user_id', 'product_id', 'customer_id', 'lead_id', 'transaction_code', 'payload', 'user_custom_mapping', 'postback_event_type_id', 'visible'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function lead() {
        return $this->belongsTo(Lead::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function scopeMonthlyPostbacks($query, $begin, $end) {
        return $query->whereBetween('created_at', [$begin, $end]);
    }

}

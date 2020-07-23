<?php

namespace App\Models\User;

use App\Enums\LeadStatus;
use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Lead extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'product_id',
        'customer_id',
        'transaction_code',
        'billet_url',
        'billet_barcode',
        'value',
        'paid_at',
        'status'
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

    public function postbacks() {
        return $this->hasMany(Postback::class);
    }

    public function scopeFinalizadas($query) {
        return $query->where('status', LeadStatus::compraFinalizada()->getStatus());
    }

    public function scopeCanceladas($query) {
        return $query->where('status', LeadStatus::compraCancelada()->getStatus());
    }

    public function scopeBoletosVencendo($query) {
        return $query->where('status', LeadStatus::boletoVencendo()->getStatus());
    }

    public function scopeBoletosVencidos($query) {
        return $query->where('status', LeadStatus::boletoVencido()->getStatus());
    }
}

<?php

namespace App\Models\User;

use App\Models\Plataform;
use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Product extends Model
{

    use MultiTenantable;

    protected $fillable = [
        'user_id', 'plataform_config_id', 'plataform_code', 'product_name', 'product_price', 'funnel_id', 'active'
    ];

    public function scopeActive($query) {
        return $query->where('active', true);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function plataformConfig() {
        return $this->belongsTo(PlataformConfig::class);
    }

    public function funnel() {
        return $this->belongsTo(Funnel::class);
    }
}

<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\ActiveRecordsTrait;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Product extends Model
{

    use MultiTenantable, ActiveRecordsTrait;

    protected $fillable = [
        'user_id', 'plataform_config_id', 'plataform_code', 'product_name', 'product_price', 'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function plataformConfig() {
        return $this->belongsTo(PlataformConfig::class);
    }

    public function plataform() {
        return $this->hasOneThrough(Plataform::class, PlataformConfig::class);
    }
}

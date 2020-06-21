<?php

namespace App\Models\User;

use App\Models\Plataform;
use App\Models\User;
use App\Traits\ActiveRecordsTrait;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class PlataformConfig extends Model
{
    use MultiTenantable, ActiveRecordsTrait;

    protected $fillable = [
        'user_id', 'plataform_id', 'plataform_key', 'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function plataform() {
        return $this->belongsTo(Plataform::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use App\Models\User\PlataformConfig;
use App\Models\User\Product;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Plataform extends Model
{
    protected $fillable = [
        'plataform_name'
    ];

    public function plataformConfigs() {
        return $this->hasMany(PlataformConfig::class);
    }
}

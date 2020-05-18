<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'marketplace_code', 'plan_name', 'num_postbacks', 'plan_value'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}

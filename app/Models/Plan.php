<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'marketplace_code', 'plan_name', 'plan_cycle_days', 'plan_value'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function features() {
        return $this->belongsToMany(Feature::class)->withPivot('enabled', 'limit');
    }

    public function enabledFeatures() {
        return $this->belongsToMany(Feature::class)->withPivot('enabled', 'limit')->wherePivot('enabled', true);
    }
}

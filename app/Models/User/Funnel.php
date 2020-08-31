<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Funnel extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'funnel_description', 'is_sales_funnel', 'active'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->hasMany(Product::class);
    }

    public function postbacks() {
        return $this->hasMany(Postback::class);
    }

    public function steps() {
        return $this->hasMany(FunnelStep::class);
    }

    public function firstStep() {
        return $this->hasOne(FunnelStep::class)
                    ->orderBy('funnel_step_sequence', 'ASC')
                    ->first();
    }

    public function nextStep(int $currentStepId) {
        return $this->hasOne(FunnelStep::class)
                    ->where('funnel_step_sequence', '>', $currentStepId)
                    ->orderBy('funnel_step_sequence', 'ASC')
                    ->first();
    }

    public function scopeByProductAndTag($query, $product_id, $tag_id) {
        return $query->where('product_id', $product_id)
                    ->where('tag_id', $tag_id);
    }
}

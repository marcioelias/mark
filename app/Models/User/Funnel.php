<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Funnel extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'product_id', 'tag_id', 'active'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function tag() {
        return $this->belongsTo(Tag::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function steps() {
        return $this->hasMany(FunnelStep::class);
    }
}

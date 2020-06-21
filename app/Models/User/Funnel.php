<?php

namespace App\Models\User;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Funnel extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'active'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}

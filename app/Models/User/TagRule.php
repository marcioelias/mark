<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class TagRule extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'product_id',
        'lead_status_id',
        'tag_id',
        'active'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function leadStatus() {
        return $this->belongsTo(leadStatus::class);
    }

    public function tag() {
        return $this->belongsTo(Tag::class);
    }
}

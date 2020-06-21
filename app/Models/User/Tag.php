<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Tag extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'tag_name'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

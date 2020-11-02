<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

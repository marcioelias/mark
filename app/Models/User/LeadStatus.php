<?php

namespace App\Models\User;

use App\Traits\NullableMultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use NullableMultiTenantable;

    protected $fillable = [
        'user_id',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

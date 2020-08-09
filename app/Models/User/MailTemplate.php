<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class MailTemplate extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'template_name', 'template'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

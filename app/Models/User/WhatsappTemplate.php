<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'template_name',
        'template_content'
    ];
}

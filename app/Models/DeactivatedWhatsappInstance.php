<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class DeactivatedWhatsappInstance extends Model
{
    protected $fillable = [
        'subdomain', 'port'
    ];
}

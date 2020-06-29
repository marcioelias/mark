<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Variable extends Model
{
    protected $fillable = [
        'variable', 'description'
    ];
}

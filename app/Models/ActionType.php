<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class ActionType extends Model
{
    protected $fillable = [
        'action_type_name',
        'action_type_description'
    ];
}

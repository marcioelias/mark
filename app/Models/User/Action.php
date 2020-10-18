<?php

namespace App\Models\User;

use App\Models\ActionType;
use App\Models\User;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = [
        'user_id',
        'action_type_id',
        'action_data',
        'executed_at'
    ];

    public function actionType() {
        return $this->belongsTo(ActionType::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models\User;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'funnel_step_action_id',
        'lead_id',
        'start_at',
        'start_period',
        'end_period',
        'delay_before_start',
        'queued_at',
        'finished_at'
    ];
}

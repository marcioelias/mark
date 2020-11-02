<?php

namespace App\Models;

use App\Models\User\FunnelStep;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class PostbackEventType extends Model
{
    protected $fillable = [
        'postback_event_type'
    ];

    public function funnelSteps() {
        return $this->hasMany(FunnelStep::class);
    }
}

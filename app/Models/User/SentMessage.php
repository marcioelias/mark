<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class SentMessage extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'lead_id',
        'funnel_step_action_id',
        'message_data',
        'return_data',
        'is_successful'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function lead() {
        return $this->belongsTo(Lead::class);
    }

    public function funnelStepAction() {
        return $this->belongsTo(FunnelStepAction::class);
    }
}

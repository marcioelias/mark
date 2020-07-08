<?php

namespace App\Models\User;

use App\Models\ActionType;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class FunnelStepAction extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'funnel_step_id', 'action_type_id', 'action_sequence', 'action_description', 'action_data'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function funnelStep() {
        return $this->belongsTo(FunnelStep::class);
    }

    public function actionType() {
        return $this->belongsTo(ActionType::class);
    }
}

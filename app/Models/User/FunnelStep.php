<?php

namespace App\Models\User;

use App\Models\PostbackEventType;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class FunnelStep extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'funnel_id', 'funnel_step_sequence', 'postback_event_type_id'
    ];

    public function user() {
        return $this->belongsto(User::class);
    }

    public function funnel() {
        return $this->belongsTo(Funnel::class);
    }

    public function postbackEventType() {
        return $this->belongsTo(PostbackEventType::class);
    }

    public function actions() {
        return $this->hasMany(FunnelStepAction::class, 'funnel_step_id');
    }

    public function funnelStepLeads() {
        return $this->hasMany(FunnelStepLead::class);
    }

    public function firstAction() {
        $this->actions()->orderBy('action_sequence', 'asc')->first();
    }
}

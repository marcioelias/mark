<?php

namespace App\Models\User;

use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class FunnelStep extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id', 'funnel_id', 'funnel_step_sequence', 'funnel_step_description', 'new_tag_id', 'delay_days', 'delay_hours'
    ];

    public function user() {
        return $this->belongsto(User::class);
    }

    public function funnel() {
        return $this->belongsTo(Funnel::class);
    }

    public function newTag() {
        return $this->belongsTo(Tag::class);
    }

    public function actions() {
        return $this->hasMany(FunnelStepAction::class, 'funnel_step_id');
    }

    public function leads() {
        return $this->hasMany(Lead::class);
    }
}

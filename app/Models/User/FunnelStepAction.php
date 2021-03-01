<?php

namespace App\Models\User;

use App\Models\ActionType;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FunnelStepAction extends Model implements HasMedia
{
    use MultiTenantable, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'funnel_step_id', 'action_type_id', 'action_sequence', 'seconds_after', 'action_description', 'action_data'
    ];

    protected $casts = [
        'action_data' => 'json'
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

    public function schedule() {
        return $this->hasMany(Schedule::class);
    }
}

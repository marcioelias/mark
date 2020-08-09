<?php

namespace App\Models\User;

use App\Constants\ScheduleStatus;
use App\Models\User;
use App\Traits\MultiTenantable;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Schedule extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'lead_id',
        'funnel_step_id',
        'funnel_step_action_id',
        'start_at',
        'start_period',
        'end_period',
        'delay_before_start',
        'queued_at',
        'finished_at'
    ];

    public function action() {
        return $this->belongsTo(FunnelStepAction::class, 'funnel_step_action_id');
    }

    public function lead() {
        return $this->belongsTo(Lead::class);
    }

    public function funnelStep() {
        return $this->belongsTo(FunnelStep::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeNotRun($query) {
        return $query->whereNull('finished_at');
    }

    public function scopeNotQueued($query) {
        return $query->whereNull('queued_at');
    }

    public function scopePending($query) {
        return $query->where('schedule_status_id', ScheduleStatus::PENDING);
    }

    public function scopeRunnable($query) {
        $now = CarbonImmutable::now();
        return $query->where('start_at', '<', $now)
                     ->whereRaw("time('$now') between `start_period` and `end_period`");
    }
}

<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\ActiveRecordsOnly;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FunnelStepLead extends Model
{
    use MultiTenantable, ActiveRecordsOnly;

    protected $fillable = [
        'user_id', 'funnel_step_id', 'lead_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function funnelStep() {
        return $this->belongsTo(FunnelStep::class);
    }

    public function lead() {
        return $this->belongsTo(Lead::class);
    }
}

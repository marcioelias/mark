<?php

namespace App\Models\User;

use App\Constants\MarketingActionStatuses;
use App\Models\ActionType;
use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MarketingAction extends Model implements HasMedia
{
    use MultiTenantable, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'marketing_action_description',
        'product_id',
        'action_type_id',
        'action_message',
        'marketing_action_status_id',
        'start_at'
    ];

    protected $casts = [
        'action_message' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function actionType() {
        return $this->belongsTo(ActionType::class);
    }

    public function customers() {
        return $this->belongsToMany(Customer::class)->withPivot(['schedule_date', 'finished_at', 'result_ok', 'result_message']);
    }

    public function scopePending(Builder $query) {
        return $query->where('marketing_action_status_id', MarketingActionStatuses::PENDING);
    }
}

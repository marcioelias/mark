<?php

namespace App\Models\User;

use App\Constants\LeadStatus as ConstantsLeadStatus;
use App\Models\LeadStatus;
use App\Models\PaymentType;
use App\Models\User;
use App\Traits\ModelUtilsTrait;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Lead extends Model
{
    use MultiTenantable, ModelUtilsTrait;

    protected $fillable = [
        'user_id',
        'product_id',
        'customer_id',
        'transaction_code',
        'payment_type_id',
        'billet_url',
        'billet_barcode',
        'value',
        'paid_at',
        'lead_status_id',
        'tag_id',
        'last_step_finished_at',
        'funnel_step_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function postbacks() {
        return $this->hasMany(Postback::class);
    }

    public function leadStatus() {
        return $this->belongsTo(LeadStatus::class);
    }

    public function tag() {
        return $this->belongsTo(Tag::class);
    }

    public function funnelStep() {
        return $this->belongsTo(FunnelStep::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

    public function paymentType() {
        return $this->belongsTo(PaymentType::class);
    }

    public function stepFinishedAt() {
        return $this->last_step_finished_at ?? $this->created_at;
    }

    public function scopeFinalizados($query) {
        return $query->where('lead_status_id', ConstantsLeadStatus::FINALIZADO);
    }

    public function scopeCancelados($query) {
        return $query->where('lead_status_id', ConstantsLeadStatus::CANCELADO);
    }

    public function scopeBoletosVencendo($query) {
        return $query->where('lead_status_id', ConstantsLeadStatus::VENCENDO);
    }

    public function scopeBoletosVencidos($query) {
        return $query->where('lead_status_id', ConstantsLeadStatus::VENCIDO);
    }
}

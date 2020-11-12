<?php

namespace App\Models\User;

use App\Models\WhatsappInstanceStatus;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WhatsappInstance extends Model
{
    use MultiTenantable;

    protected $fillable = [
        'user_id',
        'description',
        'product_id',
        'port',
        'url',
        'subdomain',
        'hash',
        'whatsapp_instance_status_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function whatsappInstanceStatus() {
        return $this->belongsTo(WhatsappInstanceStatus::class);
    }

    public function scopeSubdomain(Builder $query, string $subdomain) {
        return $query->where('subdomain', $subdomain);
    }

    public function scopeHash(Builder $query, string $hash) {
        return $query->where('hash', $hash);
    }
}

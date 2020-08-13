<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\MultiTenantable;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MailTemplate extends Model implements HasMedia
{
    use MultiTenantable, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'template_name', 'template'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

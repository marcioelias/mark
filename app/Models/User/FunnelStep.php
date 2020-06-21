<?php

namespace App\Models\User;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class FunnelStep extends Model
{
    protected $fillable = [
        'user_id', 'funnel_id', 'sequence', 'original_tag', 'new_tag'
    ];

    public function user() {
        return $this->belongsto(User::class);
    }

    public function funnel() {
        return $this->belongsTo(Funnel::class);
    }

    public function originalTag() {
        return $this->belongsTo(Tag::class);
    }

    public function newTag() {
        return $this->belongsTo(Tag::class);
    }
}

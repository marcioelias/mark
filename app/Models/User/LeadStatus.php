<?php

namespace App\Models\User;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $fillable = [
        'user_id',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

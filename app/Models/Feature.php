<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'feature', 'action_type_id', 'order'
    ];

    public function plans() {
        return $this->belongsToMany(Plan::class)->withPivot('enabled', 'limit');
    }

    public function actionType() {
        return $this->belongsTo(ActionType::class);
    }
}

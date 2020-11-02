<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MultiTenantable {
    protected static function bootMultitenantable()
    {
        if (auth()->guard('web')->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->guard('web')->id();
            });

            static::addGlobalScope('user_id', function (Builder $builder) {
                $builder->where((new self)->getTable().'.user_id', auth()->guard('web')->id());
            });
        }
    }
}

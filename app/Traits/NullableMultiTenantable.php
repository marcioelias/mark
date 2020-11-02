<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait NullableMultiTenantable {
    protected static function bootNullableMultitenantable()
    {
        if (auth()->guard('web')->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->guard('web')->id();
            });

            static::addGlobalScope('user_id', function (Builder $builder) {
                $builder->whereNull((new self)->getTable().'.user_id')
                        ->orWhere((new self)->getTable().'.user_id', auth()->guard('web')->id());
            });
        }
    }
}

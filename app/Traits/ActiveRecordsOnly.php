<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ActiveRecordsOnly {
    protected static function bootActiveRecordsOnly()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where((new self)->getTable().'.active', true);
        });
    }
}

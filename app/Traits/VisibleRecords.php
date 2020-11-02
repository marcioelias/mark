<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait VisibleRecords {
    /**
     * Automatic filter visible registers for normal users loggedin
     *
     * @return void
     */
    protected static function bootVisibleRecords()
    {
        if (auth()->guard('web')->check()) {
            static::addGlobalScope('visible', function (Builder $builder) {
                $builder->where((new self)->getTable().'.visible', true);
            });
        }
    }
}

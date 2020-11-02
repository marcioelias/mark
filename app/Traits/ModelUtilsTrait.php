<?php

namespace App\Traits;

trait ModelUtilsTrait {
    public function scopeWithHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
    }
}

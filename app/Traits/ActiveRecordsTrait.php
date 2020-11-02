<?php

namespace App\Traits;

trait ActiveRecordsTrait {
    public function scopedActive($query) {
        return $query->where('active', true);
    }
}

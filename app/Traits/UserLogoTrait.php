<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait UserLogoTrait {
    public function getUserLogo() {
        if (Auth::check()) {
            $name = explode(' ', Auth::user()->name);
        } else {
            $name = ['GUEST'];
        }
        if (count($name) == 1) {
            $initials = $name[0][0].$name[0][1];
        } else {
            $initials = $name[0][0].$name[1][0];
        }
        return strtoupper($initials);
    }
}

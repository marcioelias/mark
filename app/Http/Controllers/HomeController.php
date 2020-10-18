<?php

namespace App\Http\Controllers;

use App\Models\User\LeadStatus;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use LayoutConfigTrait;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::user()->first_login_at) {
            return redirect()->route('password.change');
        } else {
            return view('home');
        }
    }
}

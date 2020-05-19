<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // Login
    public function showLoginForm(){
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('/auth/user/login', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if( $user && !$user->activation){
            throw ValidationException::withMessages([$this->username() => trans('auth.deactivated')]);
        }
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
        if($user && !$user->active){
            throw ValidationException::withMessages([$this->username() => trans('auth.deactivated')]);
        }
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->first_login_at) {
            return redirect()->action('Auth\User\LoginController@showChangePasswordForm');
        }
    }

    public function showChangePasswordForm() {
        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true
        ];

        return view('/auth/passwords/change_password', [
            'pageConfigs' => $pageConfigs
        ]);
    }

    public function changePassword(Request $request) {
        $this->validate($request, [
            'password' => 'required|min:6|max:30|confirmed'
        ]);

        $user = Auth::guard()->user();
        $user->password = bcrypt($request->password);
        $user->first_login_at = now();
        $user->save();
        return redirect()->action('HomeController@index');
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    // public function redirectPath()
    // {
    //     $redirect = [
    //         "administrator" => "admin/dashboard"
    //     ];

    //     $role = "administrator";
    //     $this->redirectTo = $redirect[$role];
    //     if (method_exists($this, 'redirectTo')) {
    //         return $this->redirectTo();
    //     }

    //     return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    // }

    protected function authenticated(Request $request, $user)
    {
        if($user->hasRole('administrator')) {
            return redirect()->route('adminDashboard');
        }

        if($user->hasRole('director') || $user->hasRole('manager') || $user->hasRole('employee')) {
            return redirect()->route('userDashboard');
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the default credentials function to add user status check.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['status'] = 'active';  // Make sure the user has an 'active' status
        return $credentials;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        if ($this->userInactive($request)) {
            return redirect()->back()
                ->withErrors(['email' => 'Your account is not active. Please contact support.'])
                ->withInput();
        }

        return redirect()->back()->withErrors([
            $this->username() => [trans('auth.failed')],
        ])->withInput();
    }

    private function userInactive(Request $request)
    {
        $user = \App\Models\User::where($this->username(), $request->input($this->username()))->first();
        return $user && $user->status !== 'active';
    }
}

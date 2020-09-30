<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required',
            'password' => 'required'
        ]);
        if (Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember')) || Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            $id = Auth::user()->id;
            $user = User::where('id', $id)->first();
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
            $user->update(['api_token' => null]);
            $access_token = $user->createToken('Laravel Passport Create Access Token')->accessToken;

            $user->update(['api_token' => $access_token]);
            $user = $user->name;

            return redirect()->intended('/home')->with(['success' => 'Logging successfully', 'user' => $user, 'access_token' => $access_token]);
        } else {
            return redirect()->back()->with(['message' => 'username and credients']);
        }
        return back()->withInput($request->only('name', 'remember'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user !== null) {

            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
            $user->update(['api_token' => null]);
        }


        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect()->route('login')->with(['logged_out' => 'username and credients']);
    }
}

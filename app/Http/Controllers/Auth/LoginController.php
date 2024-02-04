<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAccessToken;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

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
		if(!$this->ensure($request->all(), ['email', 'password'])) return $this->error('Please enter your email and password to login', 400);

		if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
			$token = UserAccessToken::generate();
			return $this->success(['token' => $token->token, 'expires' => $token->expires_at]); //user has been logged in. Front end should store token as cookie if acceptable
		}
		return $this->error('Incorrect Username or Password', 401); //username or password was wrong
	}

	public function logout(Request $request)
	{
		// Check if the 'remember_me' cookie exists and delete it
		if ($request->hasCookie('remember_me')) {
			$cookie = $request->cookie('remember_me');
			$token = UserAccessToken::where('token', $cookie)->first();
			if ($token) $token->delete();

			auth()->logout();

			return $this->success();
		}

		auth()->logout();
		return $this->success('Logout Sucessful');
	}

}

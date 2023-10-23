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
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required'
		]);

		if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {

			if ($request->remember && $request->hasCookie('accept_cookies')) {
				$token = UserAccessToken::generate()->token;
				return redirect()->route('home')->withCookie(Cookie::make('remember_me', $token, 43830));
			}
			return redirect()->route('home');
		}
		return back()->withInput($request->only('email'));
	}

	public function logout(Request $request)
	{
		// Check if the 'remember_me' cookie exists and delete it
		if ($request->hasCookie('remember_me')) {
			$cookie = $request->cookie('remember_me');
			$token = UserAccessToken::where('token', $cookie)->first();
			if($token) $token->delete();

			auth()->logout();
			$cookie = Cookie::forget('remember_me');
			return redirect('/')->withCookie($cookie);
		}

		auth()->logout();
		return redirect('/');
	}

	public function index()
	{
		return view('auth.login');
	}
}

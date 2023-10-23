<?php

namespace App\Http\Middleware;

use App\Models\UserAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle($request, Closure $next)
	{
		if($this->authenticated()) return $next($request);

		return redirect()->route('login'); // Change to your login route.
	}


	public static function authenticated()
	{
		if (auth()->check()) {
			return true;
		}

		$cookie = request()->cookie('remember_me');
		if ($cookie) {
			$token = UserAccessToken::where('token', $cookie)->first();
			if ($token && $token->validate(true)) {
				Auth::loginUsingId($token->user_id);
				return true;
			}
		}
		return false;
	}
}

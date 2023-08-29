<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		//get 50 users near the current user
		$users[] = User::find(1);
		$users[] = $users[0];
		$users[] = $users[0];
		$users[] = $users[0];
		$users[] = $users[0];
		$users[] = $users[0];
		$users[] = $users[0];
		$users[] = $users[0];
		$users[] = $users[0];
		return view('home')->with('users', $users);
	}
}

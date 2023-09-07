<?php

namespace App\Http\Controllers;

use App\Models\User;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

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
		// Get the current user's location
		$currentUser = auth()->user();
		$currentLocation = $currentUser->location;

		// Get all users sorted by distance from the current user
		$users = User::query()->orderByDistance('location', $currentLocation)
			->limit(50)
			->pluck('id')
			->toArray();

		return view('home')->with('users', $users);
	}
}

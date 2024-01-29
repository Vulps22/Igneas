<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{


	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function get_grid(Request $request)
	{
		if (!$this->verify($request)) return $this->error("You are not authorized to perform this action", 401);

		// Get the current user's location
		$currentUser = $this->auth->user;
		$currentLocation = $currentUser->location;
		$users = null;
		if (!$currentLocation) {
			//get the users unfiltered
			$users = User::query()
				->where('id', '<>', $currentUser->id)
				->limit(50)
				->get();
		}

		if ($currentLocation) {
			// Get all users sorted by distance from the current user
			$users = User::query()
				->where('id', '<>', $currentUser->id)
				->orderByDistance('location', $currentLocation)
				->limit(50)
				->get();
		}

		if (!$users) {
			$users = [];
		}
		$profiles = [];
		/** @var \App\Models\User $user */
		foreach ($users as $user) {

			$profile = $user->profile->short_array();
			$profile['user_id'] = $user->id;
			$profile['location'] = $currentLocation ? ($profile['show_location'] ? $user->distance($currentLocation) : null) : null;
			$profiles[] = $profile;
		}

		return $this->success($profiles);
	}
}

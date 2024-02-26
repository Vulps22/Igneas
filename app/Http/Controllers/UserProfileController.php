<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserHealth;
use App\Models\UserImage;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UserProfileController extends Controller
{

	/**
	 * List user profiles based on the current user's location.
	 *
	 * Retrieves a list of user profiles, sorted by distance from the current user's location if available,
	 * or returns an unfiltered list of users if the current user's location is not available.
	 *
	 * @param \Illuminate\Http\Request $request The HTTP request object containing authentication information.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the list of user profiles, including relevant details such as display name, distance, etc.
	 */
	public function list(Request $request)
	{

		$limit = 20;
		$auth = $request->get('auth');
		$page = $request->page ?? 1;
		$offset = ($page - 1) * $limit;
		// Get the current user's location
		$currentUser = $auth->user;
		$currentLocation = $currentUser->location;
		$users = null;

		if (!$currentLocation) {
			//get the users unfiltered
			$users = User::query()
				->where('id', '<>', $currentUser->id)
				->offset($offset)
				->limit(20)
				->get();
		}

		if ($currentLocation) {
			// Get all users sorted by distance from the current user
			$users = User::query()
				->where('id', '<>', $currentUser->id)
				->with('profile')
				->orderByDistance('location', $currentLocation)
				->limit(50)
				->get();
		}

		if (!$users) {
			$users = [];
		}

		$profiles = $users->map(function ($user) use ($currentLocation) {

			$profile = $user->profile->short_array();

			$profile['user_id'] = $user->id;

			if ($currentLocation && $profile['show_location']) {
				$profile['location'] = $user->distance($currentLocation);
			} else {
				$profile['location'] = null;
			}

			return $profile;
		})->all();

		return $this->success($profiles);
	}

	/**
	 * Set the location of the authenticated user.
	 *
	 * Sets the latitude and longitude coordinates of the authenticated user's location based on the provided request data.
	 *
	 * @param \Illuminate\Http\Request $request The HTTP request object containing latitude and longitude coordinates.
	 * @return \Illuminate\Http\JsonResponse A JSON response indicating the success status of the location update.
	 */
	public function set_user_location(Request $request)
	{

		$auth = $request->get('auth');

		$required = [
			'longitude',
			'latitude'
		];

		if (!$this->ensure($request, $required)) return $this->error('Missing Information', 400);


		$latitude = $request->latitude;
		$longitude = $request->longitude;

		$user =  $auth->user;
		$user->location = new Point($latitude, $longitude);
		$user->save();

		return $this->success('Location updated successfully');
	}
	/**
	 * Save the user's profile information.
	 *
	 * Retrieves profile data from the request and saves it to the database for the authenticated user.
	 *
	 * @param \Illuminate\Http\Request $request The HTTP request object containing profile information.
	 * @return \Illuminate\Http\JsonResponse A JSON response indicating the success status of the profile update.
	 */
	public function save_user_profile(Request $request)
	{

		$auth = $request->get('auth');
		// Specify default values for each field
		$dataDefaults = [
			'display_name' => '',
			'sexuality' => '',
			'bio' => '',
			'height' => null,
			'weight' => null,
			'body_type' => '',
			'position' => '',
			'dominance' => '',
			'ethnicity' => '',
			'relationship_status' => '',
			'looking_for' => '',
			'gender' => '',
			'pronouns' => '',
			'show_location' => 0,
			'show_age' => 0,
			'health' => [
				'hiv_status' => '',
				'last_STI_test' => null,
				'on_prep' => 0,
				'show_hiv_status' => 0,
			]
		];

		// Use the fill function to fill the $data array with default values
		$data = $this->fill($request->all(), $dataDefaults);
		$user = $auth->user;
		if (!$user) return $this->error('User Not Found', 500);
		$user_id = $user->id;

		$profile = UserProfile::findOrNew($user->id);
		if (!$profile->exists) $profile['user_id'] = $user_id;

		$profile->display_name = $data['display_name'];
		$profile->sexuality = $data['sexuality'];
		$profile->bio = $data['bio'];
		$profile->height = $data['height'];
		$profile->weight = $data['weight'];
		$profile->body_type = $data['body_type'];
		$profile->position = $data['position'];
		$profile->dominance = $data['dominance'];
		$profile->ethnicity = $data['ethnicity'];
		$profile->relationship_status = $data['relationship_status'];
		$profile->looking_for = $data['looking_for'];
		$profile->gender = $data['gender'];
		$profile->pronouns = $data['pronouns'];
		$profile->show_location = $data['show_location'];
		$profile->show_age = $data['show_age'];
		$profile->save();

		$sexual_health = UserHealth::findOrNew($user->id);
		if (!$sexual_health->exists) $sexual_health['user_id'] = $user_id;
		$sexual_health->hiv_status = $data['health']['hiv_status'];
		$sexual_health->last_STI_test = $data['health']['last_test'];
		$sexual_health->on_prep = $data['health']['on_prep'];
		$sexual_health->show_hiv_status = $data['health']['show_hiv_status'];
		$sexual_health->save();

		return $this->success('Profile updated successfully');
	}

	/**
	 * Save the user's profile image.
	 *
	 * Stores the uploaded image file for the authenticated user at the specified position.
	 *
	 * @param \Illuminate\Http\Request $request The HTTP request object containing the uploaded image file and position.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the URL and position of the saved image.
	 */
	function save_user_profile_image(Request $request)
	{

		$auth = $request->get('auth');

		$position = intVal($request->position);
		$user = $auth->user;

		if (!$user) return $this->error('User not found', 401);
		if ($position < 0 || $position > 6) return $this->error('Invalid Position', 500);

		$imageFile = ImageController::store($request);

		if (!$imageFile) return $this->error('No image uploaded', 400);
		if ($imageFile === 'File Not Found') return $this->error('File Not Found');
		$imageModel = UserImage::firstOrCreate([
			'user_id' => $user->id,
			'position' => $position,
		]);

		$name = $imageFile['filename'];
		$imageModel->filename = $name;
		$url = asset(Storage::url("images/$name"));

		$imageModel->save();

		return $this->success(['url' => $url, 'position' => $position]);
	}

	/**
	 * Delete the user's profile image.
	 *
	 * Deletes the profile image associated with the authenticated user at the specified position.
	 *
	 * @param \Illuminate\Http\Request $request The HTTP request object containing the position of the image to delete.
	 * @return \Illuminate\Http\JsonResponse A JSON response indicating the success status of the image deletion.
	 */
	function delete_user_profile_image(Request $request)
	{
		$auth = $request->get('auth');

		$position = intVal($request->position);

		$user = $auth->user;
		if (!$user) return $this->error('User not found', 401);
		if ($position < 0 || $position > 6) return $this->error('Invalid Position', 500);

		$imageModel = $user->images()->where('position', $position)->first();

		$filename = $imageModel->filename;
		if (!$filename) return $this->error('File Not Found', 404);
		Storage::delete("images/$filename");
		$imageModel->filename = '';
		$imageModel->save();

		return $this->success(['position' => $position]);
	}

	/**
	 * Get the user's profile information.
	 *
	 * Retrieves the profile information for the specified user, including distance if available and age if allowed.
	 * Does not format values if the requesting user is requesting their own profile
	 *
	 * @param \App\Models\User $user The user model instance for which to retrieve profile information.
	 * @param \Illuminate\Http\Request $request The HTTP request object containing authentication information.
	 * @return \Illuminate\Http\JsonResponse A JSON response containing the user's profile information.
	 */
	public function get(User $user, Request $request)
	{
		$isMe = false;
		$auth = $request->get('auth');

		if ($auth->user->id == $user->id) $isMe = true;

		$profileArray = $user->profile->array($isMe);
		if ($profileArray['show_location'] && !$isMe) $profileArray['distance'] = $user->distance($auth->user->location);
		else $profileArray['distance'] = null;
		if (!$profileArray['show_age'] && !$isMe) $profileArray['age'] = null;

		return $this->success($profileArray);
	}
}

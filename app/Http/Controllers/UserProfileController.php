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
	public function set_user_location(Request $request)
	{

		if(!$this->verify($request)) return $this->error("You are not authorized to perform this action", 401);

		$required = [
			'longitude',
			'latitude'];

		if(!$this->ensure($request, $required)) return $this->error('Missing Information', 400);

	
		$latitude = $request->latitude;
		$longitude = $request->longitude;

		$user =  $this->auth->user;
		$user->location = new Point($latitude, $longitude);
		$user->save();

		return $this->success("Location updated successfully");
	}

	public function save_user_profile(Request $request)
	{

		if(!$this->verify($request)) return $this->error("You are not authorized to perform this action", 401);

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
				'hiv_status' => '',
				'last_STI_test' => null,
				'on_prep' => 0,
				'show_hiv_status' => 0,
			];

		// Use the fill function to fill the $data array with default values
		$data = $this->fill($request->all(), $dataDefaults);

		$user = $this->auth->user;
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
		$profile->show_location = $data['show_location'] === 'on' ? 1 : 0;
		$profile->show_age = $data['show_age'] === 'on' ? 1 : 0;
		$profile->save();

		$sexual_health = UserHealth::findOrNew($user->id);
		if (!$sexual_health->exists) $sexual_health['user_id'] = $user_id;
		$sexual_health->hiv_status = $data['hiv_status'];
		$sexual_health->last_STI_test = $data['last_STI_test'];
		$sexual_health->on_prep = $data['on_prep'] === 'on' ? 1 : 0;
		$sexual_health->show_hiv_status = $data['show_hiv_status'] === 'on' ? 1 : 0;
		$sexual_health->save();

		return $this->success('Profile updated successfully');
	}

	function save_user_profile_image(Request $request)
	{

		if(!$this->verify($request)) return $this->error("You are not authorized to perform this action", 401);


		$position = intVal($request->position);
		$imageFile = $request->file('image');
		$user = $this->auth->user;

		if (!$user) return $this->error("User not found", 401);
		if ($position < 0 || $position > 6) return $this->error('Invalid Position', 500);
		if (!$imageFile) return $this->error('No image uploaded', 400);
		if (!$imageFile->isValid()) return $this->error('Invalid image', 400);

		//upload the file
		$name = uniqid() . '.' . $imageFile->extension();

		$imageFile->storeAs('public/images', $name);

		$imageModel = UserImage::firstOrCreate([
			'user_id' => $user->id,
			'position' => $position
		]);

		$imageModel->filename = $name;
		$url = asset(Storage::url("images/$name"));
		//dump("Generated URL: $url");
		$imageModel->save();

		return $this->success(['url' => $url, 'position' => $position]);
	}

	function delete_user_profile_image(Request $request)
	{
		if(!$this->verify($request)) return $this->error("You are not authorized to perform this action", 401);

		$position = intVal($request->position);
		$user = $this->auth->user;
		if (!$user) return $this->error("User not found", 401);
		if ($position < 0 || $position > 6) return $this->error('Invalid Position', 500);
		//var_dump($user->images()->get());
		$imageModel = $user->images()->where('position', $position)->first();
		var_dump($imageModel);
		$filename = $imageModel->filename;
		if (!$filename) return $this->error('File Not Found', 404);
		Storage::delete("public/images/$filename");
		$imageModel->filename = '';
		$imageModel->save();

		return $this->success(["position" => $position]);
	}

	public function get(User $user, Request $request) {

		if(!$this->verify($request)) return $this->error("You are not authorized to perform this action", 401);

		$profileArray = $user->profile->array();
		if($profileArray['show_location']) $profileArray['distance'] = $user->distance($this->auth->user->location);
		else $profileArray['distance'] = null;
		if(!$profileArray['show_age']) $profileArray['age'] = null;

		if(!$profileArray['health']['show_hiv_status']) $profileArray['health']['hiv_status'] = null;

		return $this->success($profileArray);
		
	}

}
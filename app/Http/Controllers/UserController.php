<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserHealth;
use App\Models\UserImage;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UserController extends Controller
{
	public function set_user_location(Request $request)
	{
		$user_id = intval($request->user);
		$latitude = $request->latitude;
		$longitude = $request->longitude;

		if (!Auth::check()) return response("You are not authorized to perform this action", 401);
		if ($user_id !== Auth::user()->id) return response("You are not authorized to perform this action", 403);

		$user =  User::find($user_id);
		$user->location = new Point($latitude, $longitude);
		$user->save();

		return response("Location updated successfully");
	}

	public function save_user_profile(Request $request)
	{
		$user_id = intval($request->user);
		$profile = $request->profile;
		$sexual_health = $request->sexual_health;

		if (!Auth::check()) return response("E-UC::SavProf.01 | You are not authorized to perform this action", 401);
		if ($user_id !== Auth::user()->id) {
			return response("E-UC::SavProf.02 | You are not authorized to perform this action", 403);
		}
		$user =  User::find($user_id);
		if (!$user) return response("User not found", 401);

		$profile = UserProfile::findOrNew($user->id);
		if (!$profile->exists) $profile->user_id = $user_id;

		$profile->display_name = $request->display_name;
		$profile->sexuality = $request->sexuality;
		$profile->bio = $request->bio;
		$profile->height = $request->height;
		$profile->weight = $request->weight;
		$profile->body_type = $request->body_type;
		$profile->position = $request->position;
		$profile->dominance = $request->dominance;
		$profile->ethnicity = $request->ethnicity;
		$profile->relationship_status = $request->relationship_status;
		$profile->looking_for = $request->looking_for;
		$profile->gender = $request->gender;
		$profile->pronouns = $request->pronouns;
		$profile->show_location = $request->show_location === 'on' ? 1 : 0;
		$profile->show_age = $request->show_age === 'on' ? 1 : 0;
		$profile->save();

		$sexual_health = UserHealth::findOrNew($user->id);
		if (!$sexual_health->exists) $sexual_health->user_id = $user_id;
		$sexual_health->hiv_status = $request->hiv_status;
		$sexual_health->last_STI_test = $request->last_STI_test;
		$sexual_health->on_prep = $request->on_prep === 'on' ? 1 : 0;
		$sexual_health->show_hiv_status = $request->show_hiv_status === 'on' ? 1 : 0;
		$sexual_health->save();

		return redirect()->route('profile.editor')->with('success', 'Profile updated successfully');
	}

	function save_user_profile_image(Request $request)
	{
		$position = $request->position;
		$imageFile = $request->file('image');
		$user_id = intVal($request->user_id);

		//Authorise the user and get their details
		if (!auth()->check()) return response("E-UC::SavProfImg.01 | You are not authorized to perform this action", 401);
		if ($user_id !== Auth::user()->id) return response("E-UC::SavProfImg.02 | You are not authorized to perform this action", 403);
		$user =  User::find($user_id);

		if (!$user) return response("User not found", 401);
		if ($position < 0 || $position > 6) return response('Invalid Position', 500);
		if (!$imageFile) return response('No image uploaded', 400);
		if (!$imageFile->isValid()) return response('Invalid image', 400);

		//upload the file
		$name = uniqid() . '.' . $imageFile->extension();

		$imageFile->storeAs('public/images', $name);

		$imageModel = UserImage::firstOrCreate([
			'user_id' => auth()->user()->id,
			'position' => $position
		]);

		$imageModel->filename = $name;
		$url = Storage::url($name);
		//dump("Generated URL: $url");
		$imageModel->save();

		return response(json_encode(['url' => $url, 'position' => $position]), 200);
	}
}

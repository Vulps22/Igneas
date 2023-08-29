<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UserController extends Controller
{
	public function set_user_location(Request $request)
	{
		$user_id = intval($request->user);
		$latitude = $request->latitude;
		$longitude = $request->longitude;

		if(!Auth::check()) return response("You are not authorized to perform this action", 401);
		if($user_id !== Auth::user()->id) return response("You are not authorized to perform this action", 403);

		$user =  User::find($user_id);
		$user->location = new Point($latitude, $longitude);
		$user->save();

		return response("Location updated successfully");
	}
}

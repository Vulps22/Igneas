<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserHealth;
use App\Models\UserProfile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

	/**
	 * Where to redirect users after registration.
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
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'dob' => ['required', 'date', 'before:18 years ago'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		],
		[
			'dob.before' => 'You must be at least 18 years old to register. Valid Photograph ID will be required to verify your age.',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\Models\User
	 */
	public function create(Request $request)
	{

		$data = $request->all();

		//find user by email
		$user = User::where('email', $data['email'])->first();
		if($user) return redirect()->back()->withErrors(['error' => 'An account with this email address already exists. Please login.'])->withInput();

		$validated = $this->validator($data)->validate();
		if(!$validated) return redirect()->back()->withErrors($validated);

		$user = User::create([
			'email' => $data['email'],
			'date_of_birth' => $data['dob'],
			'password' => Hash::make($data['password']),
			'terms_accepted' => now(),
		]);
		
		if(!$user) return redirect()->back()->withErrors(['error' => 'An error occurred while creating your account. Please try again.']);

		$profile = UserProfile::create([
			'user_id' => $user->id,
		]);
		$profile->save();

		$health = UserHealth::create([
			'user_id' => $user->id,
		]);
		$health->save();

		auth()->loginUsingId($user->id);

		return redirect()->route('profile-editor');
	}
}

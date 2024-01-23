<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Models\UserHealth;
use App\Models\UserProfile;
use Carbon\Carbon;
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
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\Models\User
	 */
	public function create(Request $request)
	{

		$data = $request->all();

		if (!$this->ensure($data, ['email', 'password', 'date_of_birth'])) return $this->error('Please fill in all required fields to continue.', 400);

		//find user by email
		$user = User::where('email', $data['email'])->first();
		if ($user) return $this->error(['error' => 'An account with this email address already exists. Please login.'], 409);


		// Check if the user is over 18
		$dateOfBirth = Carbon::parse($data['date_of_birth']);
		$eighteenYearsAgo = Carbon::now()->subYears(18);

		if ($dateOfBirth->isAfter($eighteenYearsAgo)) {
			return $this->error(['error' => 'You must be 18 or older to register.'], 400);
		}


		$user = User::create([
			'email' => $data['email'],
			'date_of_birth' => $data['date_of_birth'],
			'password' => Hash::make($data['password']),
			'terms_accepted' => now(),
		]);

		if (!$user) return $this->error('An error occurred while creating your account. Please try again.', 500);

		$profile = UserProfile::create([
			'user_id' => $user->id,
		]);
		$profile->save();

		$health = UserHealth::create([
			'user_id' => $user->id,
		]);
		$health->save();

		auth()->loginUsingId($user->id);

		$token = UserAccessToken::generate()->token;

		return $this->success($token);
	}
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, HasSpatial;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'date_of_birth',
		'terms_accepted',
		'password',
		'location',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
		'location' => Point::class,
		'date_of_birth' => 'date',

	];

	public static function query(): SpatialBuilder
	{
		return parent::query();
	}

	public function profile()
	{
		return $this->hasOne(UserProfile::class);
	}

	public function health()
	{
		return $this->hasOne(UserHealth::class);
	}

	public function age()
	{
		//calculate the user's age
		return $this->date_of_birth->diffInYears(now());
	}

	public function images()
	{
		//order by position low to high
		return $this->hasMany(UserImage::class)->orderBy('position', 'asc');
	}

	public function profile_picture()
	{
		//return the first image in the collection
		return $this->hasOne(UserImage::class)->where('position', '=', 0);
	}

	public function distance(Point $point = null)
	{
		if (!$point) return "0m";

		$userLoc = $this->location;
		$userLong = $userLoc->longitude;
		$userLat = $userLoc->latitude;
		
		$pointLong = $point->longitude;
		$pointLat = $point->latitude;

		$distance = $this->haversine_distance($userLat, $userLong, $pointLat, $pointLong);

		return $distance;
/*
		$distance = $this->deg2meters($distance);

		//turn this into km
		if ($distance > 1000) {
			$distance = round($distance / 1000, 2);
			return $distance . " km";
		} else {
			$distance = round($distance, 2);
			return $distance . " m";
		}
		*/
	}

	/**
	 * The conversations that the user is a part of.
	 */
	public function conversations()
	{
		return $this->hasMany(UserConversation::class, 'user_one')->orWhere('user_two', $this->id);
	}
	/*
	65*2*3.14

	circ 2pi r
*/
	private function deg2meters($distanceDeg)
	{
		$R = 6378137;

		$rad = $distanceDeg * pi() / 180;
		return $rad * $R;
	}


	/**
	 * Outputs the distance between two points in kilometers.
	 */
	function haversine_distance($lat1, $lon1, $lat2, $lon2)
	{
		// Radius of the Earth in kilometers
		$R = 6371.0;

		// Convert latitude and longitude from degrees to radians
		$lat1 = deg2rad($lat1);
		$lon1 = deg2rad($lon1);
		$lat2 = deg2rad($lat2);
		$lon2 = deg2rad($lon2);

		// Haversine formula
		$dlon = $lon2 - $lon1;
		$dlat = $lat2 - $lat1;
		$a = (sin($dlat / 2) * 2) + cos($lat1) * cos($lat2) * (sin($dlon / 2) * 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		// Calculate the distance
		$distance = $R * $c;

		return $distance;
	}
}

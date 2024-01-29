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
	 * @property string $name
	 * @property string $email
	 * @property string date_of_birth
	 * @property bool terms_accepted
	 * @property string password
	 * @property \MatanYadaev\EloquentSpatial\Objects\Point location
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
		if (!$point) return "";

		$userLoc = $this->location;
		$userLong = $userLoc->longitude;
		$userLat = $userLoc->latitude;

		$pointLong = $point->longitude;
		$pointLat = $point->latitude;

		$distance = $this->distanceBetween($userLat, $userLong, $pointLat, $pointLong, "K");

		if ($distance < 1) return "< 1km";

		return round($distance, 2) . "km";
	}

	/**
	 * The conversations that the user is a part of.
	 */
	public function conversations()
	{
		return $this->belongsToMany(Conversation::class);
	}

	/**
	 * Calculate the distance in km between two points
	 * @param float $lat1 Latitude of point 1
	 * @param float $lon1 Longitude of point 1
	 * @param float $lat2 Latitude of point 2
	 * @param float $lon2 Longitude of point 2
	 * @param string $unit Unit of distance (K = kilometers, N = nautical miles, M = miles)
	 * @return float Distance between points in the specified unit
	 */
	function distanceBetween($lat1, $lon1, $lat2, $lon2, $unit)
	{

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
}

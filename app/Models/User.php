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
}

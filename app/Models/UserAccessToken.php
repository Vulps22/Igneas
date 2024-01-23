<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserAccessToken extends Model
{
	use HasFactory;

	protected $primaryKey = 'token';
	public $incrementing = false;
	
	protected $fillable = ['user_id', 'token', 'agent', 'expires_at'];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'expires_at' => 'date:Y-m-d',
	];

	public static function generate()
	{
		$token = new static;
		$token->user_id = auth()->id();
		$token->token = Str::uuid();
		$token->agent = request()->header('User-Agent');
		$token->expires_at = now()->addMonth();
		$token->save();
		return $token;
	}


	public function renew()
	{
		$this->expires_at = $this->expires_at->addMonth();
		$this->save();
		return !$this->expired();
	}

	public function validate($renew = false)
	{
		$isValid = !$this->expired();

		if ($isValid && $renew) $this->renew();

		return $isValid;
	}

	public function expired()
	{
		$oneYearAgo = now()->subYear();
		$tokenCreatedAt = $this->created_at;

		return $oneYearAgo->greaterThan($tokenCreatedAt) || now()->greaterThan($this->expires_at);
	}
}

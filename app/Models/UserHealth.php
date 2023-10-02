<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHealth extends Model
{
	use HasFactory;

	protected $table = 'users_health';

	protected $fillable = [
		'user_id',
		'hiv_status',
		'last_STI_test',
		'on_prep',
		'show_hiv_status',
	];

	protected $casts = [
		'last_STI_test' => 'date',
		'on_prep' => 'boolean',
		'show_hiv_status' => 'boolean',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

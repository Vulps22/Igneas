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

	public function getHIVString()
	{
		if (!$this->show_hiv_status) return '';

		if ($this->on_prep) $prep = 'on prep';
		else $prep = 'not on prep';
		return "{$this->hiv_status} | {$prep}";
	}

	public function getLastTestString()
	{
		if (!$this->show_last_STI_test) return '';
		return $this->last_STI_test->format('d/m/Y');
	}

	public function toArray()
	{
		return [
			"user_id" => $this->user_id,
			"show_hiv_status" => $this->show_hiv_status,
			"hiv_status" => $this->getHIVString(),
			"last_test" => $this->getLastTestString()
		];
	}
}

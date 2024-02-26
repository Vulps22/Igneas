<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Fillable properties for the UserHealth model.
 *
 * @property int $user_id The ID of the user associated with the health information.
 * @property string $hiv_status The HIV status of the user.
 * @property \Illuminate\Support\Carbon|null $last_STI_test The date of the user's last STI test.
 * @property bool $on_prep Indicates whether the user is on Pre-exposure Prophylaxis (PrEP).
 * @property bool $show_hiv_status Indicates whether the user's HIV status should be displayed.
 */
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

	/**
	 * Get a formatted string representation of the user's HIV status, optionally including Pre-exposure Prophylaxis (PrEP) status.
	 *
	 * @return string The formatted string representation of the user's HIV status.
	 */
	public function getHIVString()
	{
		if (!$this->show_hiv_status) return '';

		if ($this->on_prep) $prep = 'on prep';
		else $prep = 'not on prep';
		return "{$this->hiv_status} | {$prep}";
	}

	/**
	 * Get a formatted string representation of the date of the user's last STI test.
	 *
	 * @return string The formatted date of the user's last STI test (dd/mm/yyyy), or an empty string if the date is not to be shown.
	 */

	public function getLastTestString()
	{
		if (!$this->show_last_STI_test) return '';
		return $this->last_STI_test->format('d/m/Y');
	}

	/**
	 * Get an array representation of the UserHealth model instance, optionally formatting certain values based on the context.
	 *
	 * @param bool $isMe Optional. If true, returns raw values without additional formatting.
	 * @return array An array containing the user's health information, including user ID, HIV status, and last STI test date.
	 */
	public function toArray($isMe = false)
	{
		$data = [
			"user_id" => $this->user_id,
			"show_hiv_status" => $this->show_hiv_status,
			"hiv_status" => $isMe ? $this->hiv_status : $this->getHIVString(),
			"last_test" => $isMe ? $this->last_STI_test : $this->getLastTestString()
		];
		if($isMe) $data['on_prep'] = $this->on_prep;
		return $data;
	}
}

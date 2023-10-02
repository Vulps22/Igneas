<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConversation extends Model
{
	use HasFactory;

	protected $table = 'user_conversation';

	protected $fillable = [
		'user_one',
		'user_two',
	];

	public function messages()
	{
		return $this->hasMany(Message::class);
	}

	/**
	 * Get both users from field user_one and user_two
	 * @return array
	 */
	 
	public function users()
	{
		return [User::find($this->user_one), User::find($this->user_two)];
	}
}
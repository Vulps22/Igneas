<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserConversation;

class Message extends Model
{
	use HasFactory;

	protected $fillable = [
		'conversation_id',
		'sender_id',
		'text',
		'image_id',
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class);
	}

	public function sender()
	{
		return $this->belongsTo(User::class, 'sender_id');
	}

	public function image()
	{
		return $this->hasOne(UserImage::class, 'id');
	}

	public function toArray()
	{
		$data = [
			'id' => $this->id,
			'text' => $this->text,
			'image' => $this->image->imageURL(),
			'sender' => [
				'id' => $this->sender->id,
				'name' => $this->sender->profile->display_name
			]
		];

		return $data;
	}
}

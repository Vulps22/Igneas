<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	use HasFactory;

	protected $table = 'conversation_message';

	protected $fillable = [
		'user_conversation_id',
		'sender_id',
		'text',
		'image_id',
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class, 'conversation_id');
	}

	public function sender()
	{
		return $this->belongsTo(User::class, 'sender_id');
	}

	public function image()
	{
		return $this->hasOne(Image::class, 'image_id');
	}
}
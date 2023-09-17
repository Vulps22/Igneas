<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
	public function create(Request $request)
	{
		$message = new Message();
		$message->conversation_id = $request->conversation_id;
		$message->sender_id = $request->sender_id;
		$message->text = $request->text;
		$message->image_id = $request->image_id;
		$message->save();
	}
}

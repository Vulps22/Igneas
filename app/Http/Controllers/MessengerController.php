<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MessengerController extends Controller
{
	public $user;
	private $selectedConversationId;
	/**
	 * Create a new component instance.
	 */
	public function __construct()
	{
	}


	public function list_conversations(Request $request)
	{
		$auth = $request->get('auth');

		$conversations = [];
		$conversation = $auth->user->conversations;

		if ($conversation->count() === 0) return $conversations;

		foreach ($conversation as $convo) {
			$userProfile = $convo->users()->wherePivotNotIn('user_id', [$auth->user->id])->first()->profile;
			//$user = $convo->users()[0]->id === $this->user->id ? $convo->users()[1]->profile : $convo->users()[0]->profile;
			$latest = $convo->messages()->latest()->first();
			if (!$latest && $convo->id !== $this->selectedConversationId) continue; //This is a Dead Conversation (User created it but never sent a message) -- These should be hidden from the other user

			$conversations[] = [
				'id' => $convo->id,
				'user' => $userProfile->short_array(),
				'latest' => $latest
			];
		}

		return $this->success($conversations);
	}

	/**
	 * Get or create the conversation between the Authorised user and $userId
	 */
	public function getConversationForUser(Request $request)
	{

		//$request->userId is the id of the user you're talking to
		// if the conversation doesn't exist, create it
		// userId could be user_one OR user_two

		$auth = $request->get('auth');

		if (!$request->userId) return $this->error('User Id Missing', 403);

		$user = User::find($request->userId);
		if(!$user) return $this->error('User Not Found', 402);

		$conversation = $auth->user->conversations()->whereHas('users', function ($query) use ($user) {
			$query->where('users.id', $user->id);
		})->first();
		if (!$conversation) {
			$conversation = $auth->user->conversations()->create();
			$conversation->save();
			$conversation->users()->attach($user->id);
		}

		$messages = $conversation->messages()
			->orderBy('created_at', 'desc')
			->take(50)
			->get();

		return $this->success([
			'id' => $conversation->id,
			'display_as' => "{$user->profile->display_name} | {$user->age()}",
			'profile_image' => $user->profile_picture(),
			'messages' => $messages
		]);
	}

	/**
	 * get the specific conversation id between the user and the other user
	 * used to update the conversation list when a new conversation is started by another user
	 */
	public function getConversation(Request $request)
	{
		//$request->conversationId is the id of the conversation you're searching for
		// if the conversation doesn't exist, something happened that shouldn't have... do nothing
		// verify the authenticated user is one of the conversation's users
		$auth = $request->get('auth');

		$user_id = $auth->user->id;

		$conversation = Conversation::find($request->conversation_id);
		if (!$conversation) return $this->error("Conversation Not Found", 404);
		if (!$conversation->users()->wherePivot('user_id', $user_id)->first()) $this->error(403, 'Unauthorized action.');

		$userProfile = $conversation->users()->wherePivotNotIn('user_id', [$user_id])->first()->profile;

		$user = $userProfile->short_array();

		$messages = $conversation->messages()
			->orderBy('created_at', 'desc')
			->take(50)
			->get();

		$conversation = [
			'id' => $conversation->id,
			'user' => $user,
			'messages' => $messages
		];

		return $this->success($conversation);
	}

	public function createMessage(Request $request)
	{

		$auth = $request->get('auth');		

		//create a new message
		$message = Message::create([
			'conversation_id' => $request->conversation_id,
			'sender_id' => $auth->user->id,
			'text' => $request->text,
		]);
		$message->save();

		//broadcast the message
		broadcast(new MessageSent($message))->toOthers();

		return $this->success(['message' => $message->toArray()]);
	}

	public function getMessages(Request $request)
	{
		$this->authorise($request);

		//get the messages for the conversation
		$messages = Message::where('conversation_id', $request->conversation_id)->get();

		return ['messages' => $messages];
	}

	/**
	 * Authorise the user to perform the action by validating they have access to the conversation and are logged in
	 */
	private function authorise(Request $request)
	{
		if (!Auth::check()) abort(401, 'Not Authorised.');
		$convo = Conversation::find($request->conversation_id);
		if (!$convo) abort(404, 'Conversation not found.');
		if (!$convo->users()->wherePivot('user_id', Auth::user()->id)->first()) abort(403, 'Unauthorized action.');
		return true;
	}
}

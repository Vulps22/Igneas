<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
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

	public function index($userId = null)
	{
		if (!auth()->check()) return redirect()->route('login'); //user is not logged in, Redirect them

		$this->user = auth()->user();
		$this->selectedConversationId = null;
		if ($userId) {
			$this->selectedConversationId = $this->getConversationId($userId);
		}
		$conversations = $this->getConversations();
		return view('messenger', ['conversations' => $conversations, 'selectedConversation' => $this->selectedConversationId]);
	}


	private function getConversations()
	{
		$conversations = [];
		$conversation = $this->user->conversations;

		if ($conversation->count() === 0) return $conversations;

		foreach ($conversation as $convo) {
			$user = $convo->users()->wherePivotNotIn('user_id', [$this->user->id])->first()->profile;
			//$user = $convo->users()[0]->id === $this->user->id ? $convo->users()[1]->profile : $convo->users()[0]->profile;
			$latest = $convo->messages()->latest()->first();
			if (!$latest && $convo->id !== $this->selectedConversationId) continue; //This is a Dead Conversation (User created it but never sent a message) -- These should be hidden from the other user

			$conversations[] = [
				'id' => $convo->id,
				'user' => $user,
				'latest' => $latest
			];
		}

		return $conversations;
	}

	/**
	 * get the specific conversation id between the user and the other user
	 */
	public function getConversationId($userId)
	{
		//$request->userId is the id of the user you're talking to
		// if the conversation doesn't exist, create it
		// userId could be user_one OR user_two

		$conversation = $this->user->conversations()->whereHas('users', function ($query) use ($userId) {
			$query->where('id', $userId);
		})->first();
		if (!$conversation) {
			$conversation = $this->user->conversations()->create();
			$conversation->save();
			$conversation->users()->attach($userId);
		}
		return $conversation->id;
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

		$user_id = Auth::user()->id;

		$conversation = Conversation::find($request->conversationId);
		if (!$conversation) return response("Conversation Not Found", 404);
		if (!$conversation->users()->wherePivot('user_id', $user_id)->first()) abort(403, 'Unauthorized action.');

		$userProfile = $conversation->users()->wherePivotNotIn('user_id', [$user_id])->first()->profile;
		$userId = $userProfile->id;
		$userImage = $userProfile->primaryImageURL();
		$userName = $userProfile->display_name;
		$userAge = $userProfile->age();

		$user = [
			'id' => $userId,
			'name' => $userName,
			'age' => $userAge,
			'image' => $userImage,
		];

		$conversation = [
			'id' => $conversation->id,
			'user' => $user,
			'latest' => $conversation->messages()->latest()->first()
		];

		return $conversation;
	}

	public function createMessage(Request $request)
	{

		$this->authorise($request);

		//create a new message
		$message = Message::create([
			'conversation_id' => $request->conversation_id,
			'sender_id' => $request->user()->id,
			'text' => $request->text,
		]);
		$message->save();

		//broadcast the message
		broadcast(new MessageSent($message))->toOthers();

		return ['status' => 'Message Sent!', 'messages' => $message];
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

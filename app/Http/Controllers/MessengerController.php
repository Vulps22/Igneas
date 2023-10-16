<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\UserConversation;
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
		dump($userId);

		if (!auth()->check()) return redirect()->route('login'); //user is not logged in, Redirect them

		$this->user = auth()->user();
		dump($this->user->id);
		$this->selectedConversationId = null;
		if ($userId) {
			dump("User id exists");
			$this->selectedConversationId = $this->getConversationId($userId);
			dump($this->selectedConversationId);
		}
		$conversations = $this->getConversations();
		dump($conversations);
dd("DONE!");
		return view('messenger', ['conversations' => $conversations, 'selectedConversation' => $this->selectedConversationId]);
	}


	private function getConversations()
	{
		$conversations = [];
		$conversation = $this->user->conversations;

		if ($conversation->count() === 0) return $conversations;
		
		foreach ($conversation as $convo) {
			$user = $convo->users()[0]->id === $this->user->id ? $convo->users()[1]->profile : $convo->users()[0]->profile;
			$latest = $convo->messages()->where('user_conversation_id', $convo->id)->latest()->first();
			if(!$latest && $convo->id !== $this->selectedConversationId) continue;

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
		dump('Getting ConversationId');
		//$request->userId is the id of the user you're talking to
		// if the conversation doesn't exist, create it
		// userId could be user_one OR user_two
		dump("UserID for conversation search: $userId");
		dump($this->user->conversations);
		$conversation = $this->user->conversations()->where('user_one', $userId)->orWhere('user_two', $userId)->first();
		dump($conversation ?? "Not Found");
		if (!$conversation) {
			dump('Creating:');
			$conversation = $this->user->conversations()->create([
				'user_one' => $this->user->id,
				'user_two' => $userId
			]);
		}
		dump($conversation);


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

		$conversation = UserConversation::find($request->conversationId);
		if (!$conversation) return response("Conversation Not Found", 404);
		if ($conversation->user_one !== $user_id && $conversation->user_two !== $user_id) abort(403, 'Unauthorized action.');

		$userProfile = $conversation->users()[0]->id === $user_id ? $conversation->users()[1]->profile : $conversation->users()[0]->profile;
		$userId = $userProfile->user->id;
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
			'latest' => $conversation->messages()->where('user_conversation_id', $conversation->id)->latest()->first()
		];

		return $conversation;
	}

	public function createMessage(Request $request)
	{

		$this->authorise($request);

		//create a new message
		$message = Message::create([
			'user_conversation_id' => $request->conversation_id,
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
		$messages = Message::where('user_conversation_id', $request->conversation_id)->get();

		return ['messages' => $messages];
	}

	/**
	 * Authorise the user to perform the action by validating they have access to the conversation and are logged in
	 */
	private function authorise(Request $request){
		if(!Auth::check()) abort(401, 'Not Authorised.');
		$convo = UserConversation::find($request->conversation_id);
		if(!$convo) abort(404, 'Conversation not found.');
		if($convo->user_one !== auth()->user()->id && $convo->user_two !== auth()->user()->id) abort(403, 'Unauthorized action.');
		return true;
	}
}

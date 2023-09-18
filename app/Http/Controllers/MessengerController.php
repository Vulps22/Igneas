<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\UserConversation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class MessengerController extends Controller
{
	public $user;
	/**
	 * Create a new component instance.
	 */
	public function __construct()
	{
	}

	public function index($userId = null)
	{

		if (!auth()->check()) return redirect()->route('login');

		$this->user = auth()->user();
		$conversations = $this->getConversations();
		$selectedConversation = null;
		if ($userId) {
			$selectedConversation = $this->getConversation($userId);
		}

		return view('messenger', ['conversations' => $conversations, 'selectedConversation' => $selectedConversation]);
	}


	private function getConversations()
	{
		$conversations = [];
		$conversation = $this->user->conversations;

		if ($conversation->count() === 0) return $conversations;
		
		foreach ($conversation as $convo) {
			$user = $convo->users()[0]->id === $this->user->id ? $convo->users()[1]->profile : $convo->users()[0]->profile;
			$conversations[] = [
				'id' => $convo->id,
				'user' => $user,
				'latest' => $convo->messages()->where('user_conversation_id', $convo->id)->latest()->first()
			];
		}

		return $conversations;
	}

	/**
	 * get the specific conversation between the user and the other user
	 */
	public function getConversation($userId)
	{
		//$request->userId is the id of the user you're talking to
		// if the conversation doesn't exist, create it
		// userId could be user_one OR user_two

		$conversation = $this->user->conversations()->where('user_one', $userId)->orWhere('user_two', $userId)->first();
		if (!$conversation) {
			$conversation = $this->user->conversations()->create([
				'user_one' => $this->user->id,
				'user_two' => $userId
			]);
		}

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

		return ['status' => 'Message Sent!', 'messages' => $this->getMessages($request)];
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

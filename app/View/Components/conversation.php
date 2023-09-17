<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\UserConversation;

class conversation extends Component
{

	public $messages;
	public $conversation;
	public $userId;

	/**
	 * Create a new component instance.
	 */
	public function __construct(public int $conversationId)
	{
		$this->conversation = UserConversation::find($conversationId);
		$this->messages = $this->conversation->messages()->orderBy('created_at', 'asc')->get();
		$this->userId = auth()->user()->id;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.conversation');
	}
}

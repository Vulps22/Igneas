<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Profile;
use App\Models\UserProfile;

class ConversationListItem extends Component
{

	

	/**
	 * Create a new component instance.
	 */
	public function __construct(public int $conversationId, public UserProfile $user, public String|null $latestMessage)
	{
	}


	/**
	 * Get the view / contents that represent the component.
	 */
	public function render()
	{
		return view('components.conversation-list-item');
	}
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class ProfileCard extends Component
{

	public $user;

	public function mount($user_id)
	{
		$this->user =  User::find($user_id);
	}

	public function render()
	{
		return view('livewire.profile-card');
	}
}

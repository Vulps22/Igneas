<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileCard extends Component
{

	public $profile;
	public $picture_url;

	public function mount($user_id)
	{
		$user =  User::find($user_id);
		$this->profile = $user->profile;
		$this->picture_url = Storage::url($user->profile->primaryImage()->filename);

	}

	public function render()
	{
		return view('livewire.profile-card');
	}
}

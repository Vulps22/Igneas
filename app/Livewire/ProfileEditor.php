<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProfileEditor extends Component
{

	public $profile;
	public $sexual_health;
	public $images;

	public $success;
	public $error;

	protected $layout = 'layouts.app';


	public function mount()
	{
		if (!Auth::check()) return redirect()->route('login');

		$this->profile = Auth::user()->profile;
		$this->sexual_health = Auth::user()->health;

		$this->success = false;
		$this->error = false;
	}

	#[Layout('layouts.app')] 
	public function render()
	{
		return view('livewire.profile-editor');
	}

	public function save()
	{
		dd("saved");
		$profile = $this->profile;
		$sexual_health = $this->sexual_health;

		$profile->save();
		$sexual_health->save();

		$this->success = "Profile saved successfully";
	}
}

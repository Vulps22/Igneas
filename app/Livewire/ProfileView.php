<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ProfileView extends Component
{
	public $user;
	public $profile;
	public $health;
	public $photos;
	public $selectedImage;

	protected $layout = 'layouts.app';


	public function mount()
	{
		$userId = request()->route('userId');
		if (!Auth::check()) return redirect()->route('login');

		if(!$userId) return redirect()->route('home');

		$this->user = User::find($userId);
		if(!$this->user) return redirect()->route('home');

		$this->profile = $this->user->profile;
		$photos = $this->profile->images->all();

		$this->health = $this->user->health;

		foreach ($photos as $photo) {
			if($photo->filename && Storage::exists($photo->filename)) $this->photos[] = Storage::url($photo->filename);
		}

		if(!$this->photos) $this->photos[] = Storage::url('images/default.png');

		$this->selectedImage = $this->photos[0];
	}

	public function select($photo)
	{
		dd($photo);
		$this->selectedImage = $photo;
	}

	public function getBodyString()
	{
		return "{$this->profile->height}cm | {$this->profile->weight}kg | {$this->profile->body_type}";
	}

	public function getHIVString()
	{
		if(!$this->health->show_hiv_status) return '';

		if($this->health->on_prep) $prep = 'on prep';
		else $prep = 'not on prep';
		return "{$this->health->hiv_status} | {$prep}";
	}

	public function getLastTestString()
	{
		if(!$this->health->show_last_STI_test) return '';
		return $this->health->last_STI_test->format('d/m/Y');
	}

	#[Layout('layouts.app')] 
	public function render()
	{
		return view('livewire.profile-view');
	}
}

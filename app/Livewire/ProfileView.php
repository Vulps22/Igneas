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

		if (!$userId) return redirect()->route('home');

		$this->user = User::find($userId);
		if (!$this->user) return redirect()->route('home');

		$this->profile = $this->user->profile;
		$photos = $this->profile->images->all();

		$this->health = $this->user->health;

		foreach ($photos as $photo) {
			if ($photo->filename && Storage::exists($photo->filename)) $this->photos[] = Storage::url($photo->filename);
		}

		if (!$this->photos) $this->photos[] = Storage::url('images/default.png');

		$this->selectedImage = $this->photos[0];
	}

	public function select($photo)
	{
		dd($photo);
		$this->selectedImage = $photo;
	}

	public function getBodyString()
	{
		$height = $this->profile->height > 0 ? $this->profile->height. 'cm | ' : '';
		$weight = $this->profile->weight > 0 ? $this->profile->weight. 'kg | ' : '';
		$string = "{$height} {$weight} {$this->profile->body_type}";
		if($string === "  ") return '';
		return $string;
	}

	#[Layout('layouts.app')]
	public function render()
	{
		return view('livewire.profile-view');
	}
}

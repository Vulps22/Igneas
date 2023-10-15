<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ProfileImages extends Component
{

	use WithFileUploads;

	public $images;

	//protected $listeners = ['profileImageSelected' => 'handleProfileImageSelected'];

	/*public function profileImageSelected($index, $data)
	{
		dd([$index, $data]);
		$this->images[$data['index']] = $data['data'];
	}*/

	public function removeImage($filename)
	{
		unset($this->images[array_search($filename, array_column($this->images, 'name'))]);
	}

	public function mount($images)
	{
		
		$this->images = $images;
	}

	public function updatedImages()
	{
		dd("Here!");
		$this->validate([
			'images.*' => 'image|max:1024', // Validate image files
		]);

		foreach ($this->images as $image) {
			$position = $image->getClientOriginalName();
			$path = $image->store('profile-images');
			Storage::disk('public')->setVisibility($path, 'public');
			$url = Storage::disk('public')->url($path);
			$this->emit('imageUploaded', compact('position', 'url'));
		}
	}

	public function deleteImage($position)
	{
		Storage::disk('public')->delete('profile-images/' . $position);
		$this->images = array_filter($this->images, function ($image) use ($position) {
			return $image->getClientOriginalName() !== $position;
		});
		$this->emit('imageRemoved', $position);
	}

	public function render()
	{
		return view('livewire.profile-images');
	}
}

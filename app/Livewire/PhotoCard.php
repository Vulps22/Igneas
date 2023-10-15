<?php

namespace App\Livewire;

use App\Models\UserImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class PhotoCard extends Component
{

	use WithFileUploads;

	public $position;
	public $image;
	public $url;
	public $imageModel;
	public $dirty = false;
	public $user_id;

	protected $listeners = [
		'saved' => 'saveModel'
	];

	public function mount($position)
	{
		$this->position = $position;

		//find or create image model
		$this->imageModel = UserImage::firstOrCreate([
			'user_id' => auth()->user()->id,
			'position' => $position,
		]);

		if(!$this->imageModel->exists) $this->imageModel->save();

		if ($this->imageModel->filename) {
			$this->url = Storage::url($this->imageModel->filename);
		}

		$this->user_id = auth()->user()->id;
	}

	public function render()
	{
		return view('livewire.photo-card');
	}

	public function removeImage()
	{
		Storage::delete($this->imageModel->filename);
		$this->imageModel->filename = "";
		$this->imageModel->save();
		$this->image = null;
		$this->url = null;
	}


	public function updatedImage()
	{
		$name = $this->image->store("public/images");
		$this->imageModel->filename = $name;
		$this->url = Storage::url($name);
		$this->imageModel->save();
	}
}

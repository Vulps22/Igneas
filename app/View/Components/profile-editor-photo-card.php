<?php

namespace App\View\Components;

use App\Models\UserImage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ProfileEditorPhotoCard extends Component
{

	public $position;
	public $image;
	public $url;
	public $imageModel;
	public $dirty = false;
	public $user_id;

	/**
	 * Create a new component instance.
	 */
	public function __construct($position)
	{
		$this->position = $position;

		//find or create image model
		$this->imageModel = UserImage::firstOrCreate([
			'user_id' => auth()->user()->id,
			'position' => $position,
		]);

		if (!$this->imageModel->exists) $this->imageModel->save();

		if ($this->imageModel->filename) {
			$this->url = Storage::url($this->imageModel->filename);
		}

		$this->user_id = auth()->user()->id;
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.profile-editor-photo-card');
	}
}

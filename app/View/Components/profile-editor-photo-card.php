<?php

namespace App\View\Components;

use App\Models\UserImage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ProfileEditorPhotoCard extends Component
{

	/**
	 * Create a new component instance.
	 */
	public function __construct(public int $position, public String $url)
	{
	}

	/**
	 * Get the view / contents that represent the component.
	 */
	public function render(): View|Closure|string
	{
		return view('components.profile-editor-photo-card');
	}
}

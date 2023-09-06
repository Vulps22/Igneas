<?php

namespace App\Livewire;

use Livewire\Component;

class ProfileItem extends Component
{
	public $icon;
	public $value;
	public $wide;
	public $title;

	public function mount($icon, $value, $title = '', $wide = false){
		$this->icon = $icon;
		$this->value = $value;
		$this->wide = $wide;
	}

    public function render()
    {
        return view('livewire.profile-item');
    }
}

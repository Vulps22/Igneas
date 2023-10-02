<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserProfile extends Model
{
	use HasFactory;

	protected $table = 'users_profile';

	protected $fillable = [
		'user_id',
		'display_name',
		'sexuality',
		'bio',
		'height',
		'weight',
		'body type',
		'position',
		'dominance',
		'ethnicity',
		'relationship_status',
		'looking_for',
		'gender',
		'pronouns',
		'show_location',
		'show_age',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function health()
	{
		return $this->user()->health();
	}

	public function images()
	{
		//order by position low to high
		return $this->user->images();
	}

	public function primaryImage()
	{
		return $this->user->images()->first();
	}

	public function primaryImageURL()
	{
		$filename = $this->user->images()->first()->filename;
		if($filename && Storage::exists($filename)) return Storage::url($filename);
		return Storage::url('images/default.png');;
	}

	public function addImage($filename, $position = null)
	{
		if($this->images()->count() > 6) return false;

		$this->images()->create([
			'user_id' => $this->user_id, 
			'filename' => $filename,
			'position' => $position ?? $this->images()->count() - 1,
		]);

		return true;
	}

	public function age()
	{
		if($this->show_age) return $this->user->age();

		return '';
	}

}

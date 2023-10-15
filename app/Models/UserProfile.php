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
		return $this->user->health();
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

	public function getHIVString()
	{
		if (!$this->health->show_hiv_status) return '';

		if ($this->health->on_prep) $prep = 'on prep';
		else $prep = 'not on prep';
		return "{$this->health->hiv_status} | {$prep}";
	}

	public function getLastTestString()
	{
		if (!$this->health->show_last_STI_test) return '';
		return $this->health->last_STI_test->format('d/m/Y');
	}

	public function getGenderString()
	{
		$gender = explode('_', $this->gender);
		return (count($gender) > 1) ? "{$gender[0]} {$gender[1]}" : $gender[0];
	}

	public function getDominanceString()
	{
		$dominance = explode('_', $this->dominance);
		return (count($dominance) > 1) ? "{$dominance[0]} {$dominance[1]}" : $dominance[0];
	}

	public function getPositionString()
	{
		$position = explode('_', $this->position);
		return (count($position) > 1) ? "{$position[0]} {$position[1]}" : $position[0];
	}

	public function getEthnicityString()
	{
		$ethnicity = explode('_', $this->ethnicity);
		return (count($ethnicity) > 1) ? "{$ethnicity[0]} {$ethnicity[1]}" : $ethnicity[0];
	}

	public function getLookingForString()
	{
		$looking = explode('_', $this->looking_for);
		return (count($looking) > 1) ? "{$looking[0]} {$looking[1]}" : $looking[0];
	}
	public function getRelationshipString()
	{
		$relationship = explode('_', $this->relationship_status);
		return (count($relationship) > 1) ? "{$relationship[0]} {$relationship[1]}" : $relationship[0];
	}

}

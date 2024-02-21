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
		if ($filename && Storage::exists($filename)) return Storage::url($filename);
		return Storage::url('images/default.png');
	}

	public function addImage($filename, $position = null)
	{
		if ($this->images()->count() > 5) return false;

		$this->images()->create([
			'user_id' => $this->user_id,
			'filename' => $filename,
			'position' => $position ?? $this->images()->count() - 1,
		]);

		return true;
	}

	public function age()
	{
		if ($this->show_age) return $this->user->age();

		return '';
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

	function getBodyString()
	{
		if (!$this->body_type) {
			return '';
		}
	
		return ucfirst($this->body_type);
	}

	public function array()
	{
		$data = [];
		foreach ($this->fillable as $property) {
			$data[$property] = $this->$property;
		}

		$data['age'] = $this->age();
		$data['gender'] = $this->getGenderString();
		$data['dominance'] = $this->getDominanceString();
		$data['ethnicity'] = $this->getEthnicityString();
		$data['body_type'] = $this->getBodyString();
		$data['looking_for'] = $this->getLookingForString();
		$data['relationship_status'] = $this->getRelationshipString();
		$data['position'] = $this->getPositionString();

		$data['images'] = [["id" => 1, 'filename' => $this->primaryImageURL()]];
		foreach ($this->images as $image) {
			$filename = $image->filename;
			if ($filename && Storage::exists("images/$filename")) $data['images'][] = ['id' => $image->id, 'filename' => Storage::url("images/$filename")];
		}
		$data['primary_image'] = $this->primaryImageURL();
		$data['health'] = $this->health->toArray();

		//print_r($data);
		return $data;
	}

	public function short_array()
	{
		$data = [];
		$data['display_name'] = $this->display_name;
		$data['age'] = $this->show_age ? $this->age() : null;
		$data['show_location'] = $this->show_location;
		$data['image'] = $this->primaryImageURL();

		return $data;
	}
}

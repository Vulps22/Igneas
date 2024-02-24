<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $user_id
 * @property string $display_name
 * @property string $sexuality
 * @property string $bio
 * @property float $height
 * @property float $weight
 * @property string $body_type
 * @property string $position
 * @property string $dominance
 * @property string $ethnicity
 * @property string $relationship_status
 * @property string $looking_for
 * @property string $gender
 * @property string $pronouns
 * @property boolean $show_location
 * @property boolean $show_age
 */

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

	protected $casts = [
		'show_location' => 'boolean',
		'show_age' => 'boolean',
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
	/**
	 * Retrieve the primary image associated with the user.
	 *
	 * @return mixed The primary image associated with the user, or null if no image is found.
	 */

	public function primaryImage()
	{
		return $this->user->images()->first();
	}

	/**
	 * Get the URL of the primary image associated with the user profile.
	 *
	 * @return string The URL of the primary image, or the URL of a default image if no primary image is found.
	 */

	public function primaryImageURL()
	{
		$filename = $this->user->images()->first()->filename;
		if ($filename && Storage::exists($filename)) return Storage::url($filename);
		return Storage::url('images/default.png');
	}

	/**
	 * Add an image to the user's collection of images.
	 *
	 * @param string $filename The filename of the image to add.
	 * @param int|null $position Optional. The position of the image in the collection.
	 * @return bool True if the image was successfully added, otherwise false.
	 */

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

	/**
	 * Get the age of the user if age display is enabled.
	 *
	 * @return string The age of the user, or an empty string if age display is disabled.
	 */
	public function age()
	{
		if ($this->show_age) return $this->user->age();

		return '';
	}

	/**
	 * Get a formatted string representation of the user's gender.
	 *
	 * @return string The formatted string representation of the user's gender.
	 */
	public function getGenderString()
	{
		$gender = explode('_', $this->gender);
		return (count($gender) > 1) ? "{$gender[0]} {$gender[1]}" : $gender[0];
	}

	/**
	 * Get a formatted string representation of the user's dominance.
	 *
	 * @return string The formatted string representation of the user's dominance.
	 */
	public function getDominanceString()
	{
		$dominance = explode('_', $this->dominance);
		return (count($dominance) > 1) ? "{$dominance[0]} {$dominance[1]}" : $dominance[0];
	}

	/**
	 * Get a formatted string representation of the user's position.
	 *
	 * @return string The formatted string representation of the user's position.
	 */
	public function getPositionString()
	{
		$position = explode('_', $this->position);
		return (count($position) > 1) ? "{$position[0]} {$position[1]}" : $position[0];
	}

	/**
	 * Get a formatted string representation of the user's ethnicity.
	 *
	 * @return string The formatted string representation of the user's ethnicity.
	 */
	public function getEthnicityString()
	{
		$ethnicity = explode('_', $this->ethnicity);
		return (count($ethnicity) > 1) ? "{$ethnicity[0]} {$ethnicity[1]}" : $ethnicity[0];
	}

	/**
	 * Get a formatted string representation of the user's preferences for the type of relationship they are looking for.
	 *
	 * @return string The formatted string representation of the user's preferences for the type of relationship they are looking for.
	 */
	public function getLookingForString()
	{
		$looking = explode('_', $this->looking_for);
		return (count($looking) > 1) ? "{$looking[0]} {$looking[1]}" : $looking[0];
	}

	/**
	 * Get a formatted string representation of the user's relationship status.
	 *
	 * @return string The formatted string representation of the user's relationship status.
	 */
	public function getRelationshipString()
	{
		$relationship = explode('_', $this->relationship_status);
		return (count($relationship) > 1) ? "{$relationship[0]} {$relationship[1]}" : $relationship[0];
	}
	/**
	 * Get a formatted string representation of the user's body type.
	 *
	 * @return string The formatted string representation of the user's body type, or an empty string if no body type is set.
	 */

	function getBodyString()
	{
		if (!$this->body_type) {
			return '';
		}

		return ucfirst($this->body_type);
	}

	/**
	 * Retrieve an array containing the user's profile, health, and image data.
	 *
	 * This method fetches various data associated with the user's profile, health, and images. 
	 * If the optional parameter $isMe is set to true, the returned values will not be formatted 
	 * for readability and data security, intended for editing data and forms.
	 *
	 * @param bool $isMe Optional. If true, returned values will not be formatted for readability and data security.
	 * @return array An array containing the following keys:
	 * - 'user_id': The ID of the user profile.
	 * - 'display_name': The display name of the user profile.
	 * - 'sexuality': The sexuality of the user profile.
	 * - 'bio': The biography of the user profile.
	 * - 'height': The height of the user profile.
	 * - 'weight': The weight of the user profile.
	 * - 'body_type': The body type of the user profile.
	 * - 'position': The position of the user profile.
	 * - 'dominance': The dominance of the user profile.
	 * - 'ethnicity': The ethnicity of the user profile.
	 * - 'relationship_status': The relationship status of the user profile.
	 * - 'looking_for': The preferences for the type of relationship the user is looking for.
	 * - 'gender': The gender of the user profile. (Formatted string if $isMe is false)
	 * - 'age': The age of the user profile. (Formatted if $isMe is false)
	 * - 'show_location': A boolean indicating whether to show the location.
	 * - 'primary_image': The URL of the primary image associated with the user profile.
	 * - 'images': An array of image data, each containing 'id' and 'filename'. (Only if $isMe is false)
	 * - 'health': An array representation of the user's health data.
	 */
	public function array($isMe = false): array
	{
		$data = [];
		foreach ($this->fillable as $property) {
			$data[$property] = $this->$property;
		}

		if (!$isMe) {
			$data['age'] = $this->age();
			$data['gender'] = $this->getGenderString();
			$data['dominance'] = $this->getDominanceString();
			$data['ethnicity'] = $this->getEthnicityString();
			$data['body_type'] = $this->getBodyString();
			$data['looking_for'] = $this->getLookingForString();
			$data['relationship_status'] = $this->getRelationshipString();
			$data['position'] = $this->getPositionString();
			$data['images'] = [["id" => 1, 'filename' => $this->primaryImageURL()]];
			$data['primary_image'] = $this->primaryImageURL();
		}
		foreach ($this->images as $image) {
			$filename = $image->filename;
			if ($filename && Storage::exists("images/$filename")) $data['images'][] = ['id' => $image->id, 'filename' => Storage::url("images/$filename")];
		}

		$data['health'] = $this->health->toArray();

		return $data;
	}

	/**
	 * Get a short array representation of the UserProfile model instance.
	 *
	 * @return array An array containing the following keys:
	 * - 'display_name': The display name of the user profile.
	 * - 'age': The age of the user profile, or null if show_age is false.
	 * - 'show_location': A boolean indicating whether to show the location.
	 * - 'image': The URL of the primary image associated with the user profile.
	 */
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

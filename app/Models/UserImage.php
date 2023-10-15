<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
	use HasFactory;

	protected $table = 'user_images';

	protected $fillable = [
		'user_id',
		'filename',
		'position',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function profile()
	{
		return $this->user()->profile();
	}

	public function imagePath(){
		if($this->filename) return "public/images/{$this->filename}";
	}

	public function imageURL(){
		if($this->filename) return "/images/{$this->filename}";
	}
}

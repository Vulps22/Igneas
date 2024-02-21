<?php

namespace App\Http\Controllers;

use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public static function defaultImage(){
        return Storage::url('images/default.png');
    }

    /**
     * Store the user's image on the S3 bucket
     * 
     */
    public static function store(Request $request)
    {

        $file = $request->file('image');
        if (!$file) return "file not found";

        $path = $request->file('image')->store('images', 's3');
       // var_dump($path);
        if (!$path) return false;
        return [
            'filename' => basename($path),
            'url' => Storage::url($path)
        ];
    }
}

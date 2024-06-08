<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the uploaded file
        if ($request->file('image')) {
            $path = $request->file('image')->store('images', 's3');

            logger('uploading');
            logger($path);
            // Make the file publicly accessible (optional)
            Storage::disk('s3')->setVisibility($path, 'public');

            // Get the file URL
            $url = Storage::disk('s3')->url($path);
            logger($url);

            return back()->with('success', 'Image uploaded successfully')->with('url', $url);
        }

        return back()->with('error', 'Image upload failed');
    }
}

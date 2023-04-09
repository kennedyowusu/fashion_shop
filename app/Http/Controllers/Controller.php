<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveImage(UploadedFile $image, $path = 'public', $resizeWidth = null, $resizeHeight = null)
    {
        // Sanitize the filename
        $filename = time() . '_' . Str::slug($image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

        $new_filename = $path . '/' . $filename;

        // Store the image
        try {
            $imageData = $resizeWidth && $resizeHeight
                ? Image::make($image)->resize($resizeWidth, $resizeHeight)->encode()
                : $image->get();
            Storage::disk('public')->put($new_filename, $imageData);

        } catch (\Exception $e) {
            throw new \RuntimeException('Error storing image file');
        }

         // Use a Content Security Policy header
        $response = response('', 200);
        $response->header('Content-Security-Policy', 'default-src \'none\'; script-src \'self\'; connect-src \'self\'; img-src \'self\'; style-src \'self\'; font-src \'self\';');

        // Return the public URL for the saved image
        return $new_filename;
    }
}

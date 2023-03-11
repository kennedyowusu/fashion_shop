<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveImage(UploadedFile $image, $path = 'public', $resizeWidth = null, $resizeHeight = null)
    {
        // Validate input
        if (!$image->isValid()) {
            throw new \InvalidArgumentException('Invalid image file');
        }

        // Generate a unique filename
        $filename = time() . '.' . $image->getClientOriginalExtension();

        // Store the image
        try {
            $imageData = $resizeWidth && $resizeHeight
                ? Image::make($image)->resize($resizeWidth, $resizeHeight)->encode()
                : $image->get();
            Storage::disk($path)->put($filename, $imageData);
        } catch (\Exception $e) {
            throw new \RuntimeException('Error storing image file');
        }

        // Return the image URL
        return Storage::disk($path)->url($filename);
    }
}


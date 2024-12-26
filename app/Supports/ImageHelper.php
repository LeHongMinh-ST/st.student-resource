<?php

declare(strict_types=1);

namespace App\Supports;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    /**
     * Save the image to the specified folder
     *
     * @param  UploadedFile  $file
     * @param  string  $directory  The directory to save the avatar in.
     * @param  ?int  $width  width set for image
     * @param  ?int  $height  height set for image
     * @return string The path where the image is saved.
     */
    public static function uploadImage(
        mixed  $file,
        string $directory,
        ?int  $width = null,
        ?int  $height = null
    ): string {
        // Generate a unique filename for the image
        $imageName = ImageHelper::generateNameFile($file, $file->getClientOriginalExtension());

        // Check if the directory exists in the public disk, if not, create it
        if (! Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Define the storage path for the image
        $storagePath = storage_path('app/public/' . $directory . '/' . $imageName);
        $path = $directory . '/' . $imageName;

        // Save the image to the defined path with the specified width and height
        if ($width && $height) {
            Image::make($file->getRealPath())->fit($width, $height)->save(
                $storagePath
            );
        } else {
            Image::make($file->getRealPath())->save($storagePath);
        }

        return $path;
    }

    public static function generateNameFile(mixed $file, string $originalExtension = ''): string
    {
        // Generate a unique filename for the image
        $strSecret = '!@#$%^&*()_+QBGFTNKU' . time() . rand(111111, 999999);
        $filenameMd5 = md5($file . $strSecret);
        $nameFile = date('Y_m_d') . '_' . $filenameMd5;
        if ($originalExtension) {
            $nameFile .= '.' . $originalExtension;
        }
        return $nameFile;
    }
}

<?php

declare(strict_types=1);

namespace App\Supports;

use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;

class AvatarHelper
{
    public const QUALITY_AVATAR = 100;

    /**
     * Creates an avatar image from a given name and saves it to the specified directory with the given filename.
     *
     * @param  string  $name  The name to create the avatar from.
     * @param  string  $directory  The directory to save the avatar in.
     * @param  string  $filename  The filename for the saved avatar.
     * @param  int  $quality  The quality of the saved avatar image (default is QUALITY_AVATAR).
     * @return string The path where the avatar image is saved.
     */
    public static function createAvatar(
        string $name,
        string $directory,
        string $filename,
        int $quality = self::QUALITY_AVATAR
    ): string {
        // Create a new Avatar instance
        $avatar = new Avatar();
        // Define the storage path for the avatar image
        $storagePath = storage_path('app/public');
        // Define the full path including directory and filename
        $path = $directory . '/' . $filename . '.png';

        // Check if the directory exists in the public disk, if not, create it
        if (! Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Create the avatar from the name and save it to the defined path with the specified quality
        $avatar->create($name)->save($storagePath . '/' . $path, $quality);

        // Return the path where the avatar image is saved
        return $path;
    }
}

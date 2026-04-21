<?php

namespace App\Support;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CloudinaryStorage
{
    public static function upload(UploadedFile $file, string $folder, string $resourceType = 'auto'): string
    {
        return Cloudinary::upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => $resourceType,
        ])->getSecurePath();
    }

    public static function delete(?string $storedPath, ?string $legacyLocalPrefix = null): void
    {
        if (!$storedPath) {
            return;
        }
        Cloudinary::uploadApi()->destroy($storedPath, ['resource_type' => 'image']);

        $localPath = ltrim($storedPath, '/');
        if ($legacyLocalPrefix !== null && !str_starts_with($localPath, trim($legacyLocalPrefix, '/') . '/')) {
            $localPath = trim($legacyLocalPrefix, '/') . '/' . $localPath;
        }

        Storage::disk('public')->delete($localPath);
    }


}

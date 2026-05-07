<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadsFiles
{
    public function uploadImage(?UploadedFile $file, string $path, ?string $oldImage = null): ?string
    {
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        if ($file) {
            return $file->store($path, 'public');
        }

        return null;
    }

    public function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
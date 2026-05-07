<?php

namespace App\Services;

use App\Models\Chirp;
use App\Models\User;
use App\Traits\UploadsFiles;

class ChirpService
{
    use UploadsFiles;

    public function createChirp(array $data, User $user): Chirp
    {
        $imagePath = isset($data['image']) ? $this->uploadImage($data['image'], 'chirp-images') : null;

        return $user->chirps()->create([
            'message' => $data['message'],
            'image'   => $imagePath,
        ]);
    }

    public function updateChirp(Chirp $chirp, array $data): Chirp
    {
        $imagePath = $chirp->image;

        if ($data['remove_image'] ?? false) {
            $this->deleteImage($chirp->image);
            $imagePath = null;
        } elseif (isset($data['image'])) {
            $imagePath = $this->uploadImage($data['image'], 'chirp-images', $chirp->image);
        }

        $chirp->update([
            'message' => $data['message'],
            'image'   => $imagePath,
        ]);

        return $chirp;
    }

    public function deleteChirp(Chirp $chirp): void
    {
        $this->deleteImage($chirp->image);
        $chirp->delete();
    }
}
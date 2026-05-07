<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Like; // 1. Dodany brakujący import modelu Like
use App\Http\Resources\ChirpResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LikeController extends Controller
{
    use AuthorizesRequests;

    public function toggle(Chirp $chirp): JsonResponse
    {
        // Sprawdzenie uprawnień przez LikePolicy
        $this->authorize('toggle', [Like::class, $chirp]);

        $user = auth()->user();
        $like = $chirp->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $chirp->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        // Ładujemy licznik do zoptymalizowanego odczytu
        $chirp->loadCount('likes');

        return response()->json([
            'liked' => $liked,
            'count' => $chirp->likes_count, // 2. Przywrócony klucz 'count', którego szuka front-end
            'chirp' => new ChirpResource($chirp),
        ]);
    }
}
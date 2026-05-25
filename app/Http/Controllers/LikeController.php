<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Like;
use App\Http\Resources\ChirpResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LikeController extends Controller
{
    use AuthorizesRequests;

    // Zwracaj bezpośrednio zasób (ChirpResource), a nie surowy JsonResponse
    public function toggle(Chirp $chirp): ChirpResource
    {
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

        $chirp->loadCount('likes');

        // Prawidłowe użycie Resource z obiektem Meta
        return (new ChirpResource($chirp))->additional([
            'meta' => [
                'liked' => $liked,
                'count' => $chirp->likes_count,
            ]
        ]);
    }
}
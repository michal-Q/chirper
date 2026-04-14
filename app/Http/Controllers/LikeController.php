<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    public function toggle(Chirp $chirp): JsonResponse
    {
        $user = auth()->user();

        $like = $chirp->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $chirp->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $chirp->likes()->count(),
        ]);
    }
}
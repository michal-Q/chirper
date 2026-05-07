<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chirp;

class LikePolicy
{
    public function toggle(User $user, Chirp $chirp): bool
    {
        return $user !== null; // Zalogowany użytkownik może polubić. Można tu dodać np. zakaz lajkowania własnych chirpów: return $user->id !== $chirp->user_id;
    }
}
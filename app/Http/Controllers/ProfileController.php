<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UpdateProfileNameRequest;
use App\Http\Requests\UpdateProfileEmailRequest;
use App\Http\Requests\UpdateProfilePasswordRequest;
use App\Http\Requests\UpdateProfileAvatarRequest;

class ProfileController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        return view('profile', ['user' => Auth::user()]);
    }

    public function updateName(UpdateProfileNameRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->route('profile')->with('success', 'Username has been changed.');
    }

    public function updateEmail(UpdateProfileEmailRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->route('profile')->with('success', 'Adres e-mail has been changed.');
    }

    public function updatePassword(UpdateProfilePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile')->with('success', 'Password has been changed.');
    }

    public function updateAvatar(UpdateProfileAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return redirect()->route('profile')->with('success', 'Profile picture has been changed.');
    }

    public function deleteAvatar(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('profile')->with('success', 'Profile picture has been deleted.');
    }

    public function viewProfile(\App\Models\User $user): \Illuminate\View\View
    {
        $chirps = $user->chirps()
            ->with(['user', 'likes'])
            ->latest('updated_at')
            ->get();

        return view('viewprofile', [
            'user' => $user,
            'chirps' => $chirps
        ]);
    }
}
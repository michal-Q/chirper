<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        return view('profile', ['user' => Auth::user()]);
    }

    public function updateName(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('profile')->with('success', 'Username has been changed.');
    }

    public function updateEmail(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,'.Auth::id(),
        ]);

        Auth::user()->update($validated);

        return redirect()->route('profile')->with('success', 'Adres e-mail has been changed.');
    }

    public function updatePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(5)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile')->with('success', 'Password has been changed.');
    }

    public function updateAvatar(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update(['avatar' => $path]);

        return redirect()->route('profile')->with('success', 'Profile picture has been changed.');
    }

    public function deleteAvatar(): \Illuminate\Http\RedirectResponse
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use App\Models\Chirp;

class ChirpController extends Controller
{
    use AuthorizesRequests;

    public function index(): \Illuminate\View\View
    {
        $chirps = Chirp::with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('home', ['chirps' => $chirps]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chirp-images', 'public');
        }

        auth()->user()->chirps()->create([
            'message' => $validated['message'],
            'image'   => $imagePath,
        ]);

        return redirect('/')->with('success', 'Your chirp has been posted!');
    }

    public function edit(Chirp $chirp): \Illuminate\View\View
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', compact('chirp'));
    }

    public function update(Request $request, Chirp $chirp): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message'      => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'remove_image' => 'nullable|boolean',
        ]);

        $imagePath = $chirp->image;

        if ($request->boolean('remove_image')) {
            if ($chirp->image) {
                Storage::disk('public')->delete($chirp->image);
            }
            $imagePath = null;
        } elseif ($request->hasFile('image')) {
            if ($chirp->image) {
                Storage::disk('public')->delete($chirp->image);
            }
            $imagePath = $request->file('image')->store('chirp-images', 'public');
        }

        $chirp->update([
            'message' => $validated['message'],
            'image'   => $imagePath,
        ]);

        return redirect('/')->with('success', 'Chirp updated!');
    }

    public function destroy(Chirp $chirp): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $chirp);

        if ($chirp->image) {
            Storage::disk('public')->delete($chirp->image);
        }

        $chirp->delete();

        return redirect('/')->with('success', 'Chirp deleted!');
    }
}
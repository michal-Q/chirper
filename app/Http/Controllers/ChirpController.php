<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Chirp;

class ChirpController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chirps = Chirp::with('user')
        ->latest()
        ->take(50)  // Limit to 50 most recent chirps
        ->get();

        return view('home', ['chirps' => $chirps]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'message' => 'required|string|max:255',
    ]);

    // Use the authenticated user
    auth()->user()->chirps()->create($validated);

    return redirect('/')->with('success', 'Your chirp has been posted!');
}

public function edit(Chirp $chirp)
{
    $this->authorize('update', $chirp);

    return view('chirps.edit', compact('chirp'));
}

public function update(Request $request, Chirp $chirp)
{
    $this->authorize('update', $chirp);

    $validated = $request->validate([
        'message' => 'required|string|max:255',
    ]);

    $chirp->update($validated);

    return redirect('/')->with('success', 'Chirp updated!');
}

public function destroy(Chirp $chirp)
{
    $this->authorize('delete', $chirp);

    $chirp->delete();

    return redirect('/')->with('success', 'Chirp deleted!');
}
}

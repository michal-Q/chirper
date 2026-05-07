<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Services\ChirpService;
use App\Http\Requests\StoreChirpRequest;
use App\Http\Requests\UpdateChirpRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChirpController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private ChirpService $chirpService
    ) {}

    public function index(): View
    {
        // Rozwiązanie problemu N+1: ładowanie relacji 'user' oraz agresywne pobieranie licznika relacji 'likes'
        $chirps = Chirp::with('user')
            ->withCount('likes')
            ->latest('updated_at')
            ->take(50)
            ->get();

        return view('home', ['chirps' => $chirps]);
    }

    public function store(StoreChirpRequest $request): RedirectResponse
    {
        $this->chirpService->createChirp($request->validated(), $request->user());

        return redirect('/')->with('success', 'Your chirp has been posted!');
    }

    public function edit(Chirp $chirp): View
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', compact('chirp'));
    }

    public function update(UpdateChirpRequest $request, Chirp $chirp): RedirectResponse
    {
        $this->chirpService->updateChirp($chirp, $request->validated());

        return redirect('/')->with('success', 'Chirp updated!');
    }

    public function destroy(Chirp $chirp): RedirectResponse
    {
        $this->authorize('delete', $chirp);

        $this->chirpService->deleteChirp($chirp);

        return redirect('/')->with('success', 'Chirp deleted!');
    }
}
<x-layout>
    <x-slot:title>
        User profile {{ $user->name }}
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body items-center text-center">
                <div class="avatar mb-4">
                    <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="{{ $user->avatarUrl() ? $user->avatarUrl() : 'https://avatars.laravel.cloud/anonymous' }}" 
                             alt="Avatar użytkownika {{ $user->name }}" />
                    </div>
                </div>
                <h2 class="card-title text-3xl font-bold">{{ $user->name }}</h2>
                <p class="text-base-content/60">Joined {{ $user->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <h3 class="text-2xl font-bold mt-8 mb-4">Chirps</h3>

        <div class="space-y-4">
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-base-content/60">This user hasn't posted any chirps yet.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
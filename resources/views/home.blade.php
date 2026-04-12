<x-layout>
    <x-slot:title>
        Home Feed
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Latest Chirps</h1>

        <!-- Chirp Form -->
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <form method="POST" action="/chirps" enctype="multipart/form-data">
                    @csrf
                    <div class="form-control w-full">
                        <textarea name="message"
                                  placeholder="What's on your mind?"
                                  class="textarea textarea-bordered w-full resize-none @error('message') textarea-error @enderror"
                                  rows="4"
                                  maxlength="255">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Image preview -->
                    <div id="chirp-preview-container" class="hidden">
                        <div class="mt-4 relative inline-block group">
                            <div class="size-20 rounded-lg ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden shadow-md">
                                <img id="chirp-image-preview" src="" class="w-full h-full object-cover" />
                            </div>
                            <button type="button"
                                    onclick="clearChirpImage()"
                                    class="btn btn-circle btn-xs btn-error absolute -top-2 -right-2 shadow opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @error('image')
                        <div class="label mt-1">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <input type="file" id="chirp-image-input" name="image" accept="image/*" class="hidden" onchange="previewChirpImage(event)">
                        <button type="button" onclick="document.getElementById('chirp-image-input').click()" class="btn btn-ghost btn-sm" title="Dodaj zdjęcie">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">Chirp</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Feed -->
        <div class="space-y-4 mt-8">
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <div class="hero py-12">
                    <div class="hero-content text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4 text-base-content/60">No chirps yet. Be the first to chirp!</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function previewChirpImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('chirp-image-preview');
            const container = document.getElementById('chirp-preview-container');
            if (file) {
                if (preview.src && preview.src.startsWith('blob:')) {
                    URL.revokeObjectURL(preview.src);
                }
                preview.src = URL.createObjectURL(file);
                container.classList.remove('hidden');
            }
        }

        function clearChirpImage() {
            const input = document.getElementById('chirp-image-input');
            const preview = document.getElementById('chirp-image-preview');
            const container = document.getElementById('chirp-preview-container');
            input.value = '';
            container.classList.add('hidden');
            if (preview.src && preview.src.startsWith('blob:')) {
                URL.revokeObjectURL(preview.src);
            }
            preview.src = '';
        }
    </script>
</x-layout>
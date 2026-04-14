<x-layout>
    <x-slot:title>
        Edit Chirp
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Edit Chirp</h1>

        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <form method="POST" action="/chirps/{{ $chirp->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-control w-full">
                        <textarea name="message"
                                  class="textarea textarea-bordered w-full resize-none @error('message') textarea-error @enderror"
                                  rows="4"
                                  maxlength="255"
                                  required>{{ old('message', $chirp->message) }}</textarea>
                        @error('message')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Current / new image preview -->
                    <div class="mt-4">
                        {{-- Existing image --}}
                        <div id="existing-image-wrapper" @if(!$chirp->image) class="hidden" @endif>
                            <p class="text-sm text-base-content/60 mb-2">Current image</p>
                            <div class="relative inline-block group">
                                <div class="size-24 rounded-lg overflow-hidden ring ring-base-300 ring-offset-2 shadow">
                                    <img id="edit-image-preview"
                                         src="{{ $chirp->image ? Storage::url($chirp->image) : '' }}"
                                         class="w-full h-full object-cover" />
                                </div>
                                <button type="button"
                                        onclick="removeEditImage()"
                                        class="btn btn-circle btn-xs btn-error absolute -top-2 -right-2 shadow opacity-0 group-hover:opacity-100 transition-opacity"
                                        title="Delete image">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden flag for image removal -->
                    <input type="hidden" name="remove_image" id="remove-image-flag" value="0" />

                    @error('image')
                        <div class="label mt-1">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="card-actions justify-between mt-6">
                        <a href="/" class="btn btn-ghost btn-sm">Cancel</a>

                        <div class="flex items-center gap-2">
                            <input type="file" id="edit-image-input" name="image" accept="image/*" class="hidden" onchange="previewEditImage(event)">
                            <button type="button"
                                    onclick="document.getElementById('edit-image-input').click()"
                                    class="btn btn-ghost btn-sm"
                                    title="{{ $chirp->image ? 'Zmień zdjęcie' : 'Dodaj zdjęcie' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm">Update Chirp</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewEditImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('edit-image-preview');
            const wrapper = document.getElementById('existing-image-wrapper');
            const flag = document.getElementById('remove-image-flag');

            if (file) {
                if (preview.src && preview.src.startsWith('blob:')) {
                    URL.revokeObjectURL(preview.src);
                }
                preview.src = URL.createObjectURL(file);
                wrapper.classList.remove('hidden');
                flag.value = '0';
            }
        }

        function removeEditImage() {
            const preview = document.getElementById('edit-image-preview');
            const wrapper = document.getElementById('existing-image-wrapper');
            const input = document.getElementById('edit-image-input');
            const flag = document.getElementById('remove-image-flag');

            if (preview.src && preview.src.startsWith('blob:')) {
                URL.revokeObjectURL(preview.src);
            }
            preview.src = '';
            input.value = '';
            wrapper.classList.add('hidden');
            flag.value = '1';
        }
    </script>
</x-layout>
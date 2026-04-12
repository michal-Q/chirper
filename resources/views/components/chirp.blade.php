@props(['chirp'])
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex space-x-3">
            <div class="avatar">
                <div class="size-10 rounded-full">
                    <img src="{{ $chirp->user ? $chirp->user->avatarUrl() : 'https://avatars.laravel.cloud/anonymous' }}"
                         alt="{{ $chirp->user ? $chirp->user->name : 'Anonymous' }}'s avatar"
                         class="rounded-full" />
                </div>
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-semibold">{{ $chirp->user ? $chirp->user->name : 'Anonymous' }}</span>
                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60">{{ $chirp->created_at->diffForHumans() }}</span>
                    </div>

                    @if (auth()->check() && auth()->id() == $chirp->user_id)
                        <div class="flex gap-1">
                            <a href="/chirps/{{ $chirp->id }}/edit" class="btn btn-ghost btn-xs">Edit</a>
                            <form method="POST" action="/chirps/{{ $chirp->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Do you want to delete this chirp?')"
                                        class="btn btn-ghost btn-xs text-error">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>

                <p class="mt-1">{{ $chirp->message }}</p>

                @if ($chirp->image)
                    <div class="mt-3">
                        <div class="cursor-pointer" onclick="document.getElementById('image_modal_{{ $chirp->id }}').showModal()">
                            <img src="{{ Storage::url($chirp->image) }}"
                                 alt="Chirp image"
                                 class="aspect-square w-full object-cover rounded-xl shadow-sm border border-base-300 hover:opacity-90 transition" />
                        </div>

                        <dialog id="image_modal_{{ $chirp->id }}" class="modal">
                            <div class="modal-box p-0 max-w-none w-auto max-h-[90vh] bg-transparent shadow-none flex items-center justify-center">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4 text-white bg-black/50 hover:bg-black/70 z-50">✕</button>
                                </form>
                                <img src="{{ Storage::url($chirp->image) }}"
                                     class="max-w-[95vw] max-h-[90vh] object-contain rounded-lg shadow-2xl" />
                            </div>
                            <form method="dialog" class="modal-backdrop bg-black/90">
                                <button>close</button>
                            </form>
                        </dialog>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
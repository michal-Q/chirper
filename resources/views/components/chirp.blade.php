@props(['chirp'])

@php
    $likeCount = $chirp->likes->count();
    $isLiked = auth()->check() && $chirp->likes->contains('user_id', auth()->id());
@endphp

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
                        <a href="{{ route('viewprofile', $chirp->user) }}" class="text-sm font-semibold hover:underline">{{ $chirp->user ? $chirp->user->name : 'Anonymous' }}</a>
                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60">{{ $chirp->updated_at->diffForHumans() }}</span>
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

                <!-- Like button -->
                <div class="flex justify-end mt-3">
                    @auth
                        <button type="button"
                                onclick="toggleLike(this, {{ $chirp->id }})"
                                data-liked="{{ $isLiked ? 'true' : 'false' }}"
                                data-count="{{ $likeCount }}"
                                class="flex items-center gap-1.5 cursor-default px-1 py-0.5 rounded text-xs font-medium {{ $isLiked ? 'text-error' : 'text-base-content/50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 viewBox="0 0 24 24"
                                 fill="{{ $isLiked ? 'currentColor' : 'none' }}"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="text-xs font-medium">{{ $likeCount }}</span>
                        </button>
                    @else
                        <div class="flex items-center gap-1.5 text-base-content/40 px-2 py-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="text-xs font-medium">{{ $likeCount }}</span>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
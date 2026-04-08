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
                                    onclick="return confirm('Czy na pewno chcesz usunąć ten chirp?')"
                                    class="btn btn-ghost btn-xs text-error">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
                <p class="mt-1">{{ $chirp->message }}</p>
            </div>
        </div>
    </div>
</div>
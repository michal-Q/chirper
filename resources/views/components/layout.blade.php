<!DOCTYPE html>
<html lang="en" data-theme="lofi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) ? $title . ' - Chirper' : 'Chirper' }}</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-base-200 font-sans">
<nav class="navbar bg-base-100">
<div class="navbar-start">
<a href="/" class="btn btn-ghost text-xl">🐦 Chirper</a>
</div>
<div class="navbar-end gap-2">
    @auth
        <a href="{{ route('profile') }}" class="btn btn-ghost btn-sm">{{ auth()->user()->name }}</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">Logout</button>
        </form>
    @else
        <a href="/login" class="btn btn-ghost btn-sm">Sign In</a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
    @endauth
</div>
</nav>

<!-- Success Toast -->
@if (session('success'))
    <div class="toast toast-top toast-center">
        <div class="alert alert-success animate-fade-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<main class="flex-1 container mx-auto px-4 py-8">
    {{ $slot }}
</main>

<footer class="footer footer-center p-5 bg-base-300 text-base-content text-xs">
    <div>
        <p>© 2025 Chirper - Built with Laravel and ❤️</p>
    </div>
</footer>

<script>
    async function toggleLike(btn, chirpId) {
        const svg = btn.querySelector('svg');
        const counter = btn.querySelector('span');
        const isLiked = btn.dataset.liked === 'true';
        const count = parseInt(btn.dataset.count);

        // Optimistic update
        const newLiked = !isLiked;
        const newCount = newLiked ? count + 1 : count - 1;

        btn.dataset.liked = newLiked;
        btn.dataset.count = newCount;
        counter.textContent = newCount;
        svg.setAttribute('fill', newLiked ? 'currentColor' : 'none');
        btn.classList.toggle('text-error', newLiked);
        btn.classList.toggle('text-base-content/50', !newLiked);
        

        try {
            const response = await fetch(`/chirps/${chirpId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) throw new Error();

            const data = await response.json();
            btn.dataset.liked = data.liked;
            btn.dataset.count = data.count;
            counter.textContent = data.count;
            svg.setAttribute('fill', data.liked ? 'currentColor' : 'none');
            btn.classList.toggle('text-error', data.liked);
            btn.classList.toggle('text-base-content/50', !data.liked);
        } catch {
            // Rollback
            btn.dataset.liked = isLiked;
            btn.dataset.count = count;
            counter.textContent = count;
            svg.setAttribute('fill', isLiked ? 'currentColor' : 'none');
            btn.classList.toggle('text-error', isLiked);
            btn.classList.toggle('text-base-content/50', !isLiked);
        }
    }
</script>
</body>
</html>
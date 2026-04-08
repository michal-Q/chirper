<x-layout>
    <x-slot:title>
        Profil
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">My profile</h1>

        <div class="space-y-4 mt-8">

            {{-- Name --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-base-content/60 mb-1">User name</p>
                            <p class="font-semibold">{{ $user->name }}</p>
                        </div>
                        <button class="btn btn-ghost btn-sm"
                            onclick="const m = document.getElementById('modal-name'); m.querySelector('form').reset(); m.showModal()">
                            Edit
                        </button>
                    </div>
                </div>
            </div>

            {{-- Email --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-base-content/60 mb-1">Adres e-mail</p>
                            <p class="font-semibold">{{ $user->email }}</p>
                        </div>
                        <button class="btn btn-ghost btn-sm"
                            onclick="const m = document.getElementById('modal-email'); m.querySelector('form').reset(); m.showModal()">
                            Edit
                        </button>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-base-content/60 mb-1">Password</p>
                            <p class="font-semibold tracking-widest">••••••••</p>
                        </div>
                        <button class="btn btn-ghost btn-sm"
                            onclick="const m = document.getElementById('modal-password'); m.querySelector('form').reset(); m.showModal()">
                            Edit
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal: Name --}}
    <dialog id="modal-name" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Change user name</h3>
            <form method="POST" action="{{ route('profile.name') }}">
                @csrf
                @method('PATCH')
                <div class="form-control w-full">
                    <label class="floating-label mb-6">
                        <input type="text"
                               name="name"
                               placeholder="Jan Kowalski"
                               value="{{ old('name', $user->name) }}"
                               class="input input-bordered w-full @error('name') input-error @enderror"
                               required>
                        <span>New name</span>
                    </label>
                    @error('name')
                        <div class="label -mt-4 mb-2">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost btn-sm"
                        onclick="document.getElementById('modal-name').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    {{-- Modal: Email --}}
    <dialog id="modal-email" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Change adres e-mail</h3>
            <form method="POST" action="{{ route('profile.email') }}">
                @csrf
                @method('PATCH')
                <div class="form-control w-full">
                    <label class="floating-label mb-6">
                        <input type="email"
                               name="email"
                               placeholder="mail@example.com"
                               value="{{ old('email', $user->email) }}"
                               class="input input-bordered w-full @error('email') input-error @enderror"
                               required>
                        <span>New e-mail</span>
                    </label>
                    @error('email')
                        <div class="label -mt-4 mb-2">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost btn-sm"
                        onclick="document.getElementById('modal-email').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    {{-- Modal: Password --}}
    <dialog id="modal-password" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Change password</h3>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PATCH')
                <div class="form-control w-full">
                    <label class="floating-label mb-6">
                        <input type="password"
                               name="current_password"
                               placeholder="•••••"
                               class="input input-bordered w-full @error('current_password') input-error @enderror"
                               required>
                        <span>Current password</span>
                    </label>
                    @error('current_password')
                        <div class="label -mt-4 mb-2">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                    <label class="floating-label mb-6">
                        <input type="password"
                               name="password"
                               placeholder="•••••"
                               class="input input-bordered w-full @error('password') input-error @enderror"
                               required>
                        <span>New password</span>
                    </label>
                    @error('password')
                        <div class="label -mt-4 mb-2">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                    <label class="floating-label mb-6">
                        <input type="password"
                               name="password_confirmation"
                               placeholder="•••••"
                               class="input input-bordered w-full"
                               required>
                        <span>Confirm new password</span>
                    </label>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost btn-sm"
                        onclick="document.getElementById('modal-password').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    {{-- Auto-open modal on validation error --}}
    @if ($errors->has('name'))
        <script>document.getElementById('modal-name').showModal();</script>
    @elseif ($errors->has('email'))
        <script>document.getElementById('modal-email').showModal();</script>
    @elseif ($errors->has('current_password') || $errors->has('password'))
        <script>document.getElementById('modal-password').showModal();</script>
    @endif

</x-layout>
<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <p class="text-sm font-black uppercase text-teal-700">Connexion</p>
        <h1 class="mt-2 text-2xl font-black text-slate-950">Retour à tes comparaisons.</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Compte démo : <strong>demo@immoradar.test</strong> / <strong>password</strong>.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-700 shadow-sm focus:ring-teal-600" name="remember">
                <span class="ms-2 text-sm text-slate-600">Rester connecté</span>
            </label>
        </div>

        <div class="flex items-center justify-between gap-3 pt-2">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-slate-600 underline decoration-teal-300 underline-offset-4 hover:text-slate-950" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif

            <x-primary-button>
                Connexion
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

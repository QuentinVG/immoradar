<x-guest-layout>
    <div class="mb-6">
        <p class="text-sm font-black uppercase text-teal-700">Créer mon espace</p>
        <h1 class="mt-2 text-2xl font-black text-slate-950">Comparer mes biens sans me raconter d’histoire.</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Inscription volontairement protégée : un code d’accès évite les comptes bot sur une instance publique.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        @if(config('app.registration_access_code'))
            <div>
                <x-input-label for="access_code" value="Code d’accès" />
                <x-text-input id="access_code" class="mt-1 block w-full" type="text" name="access_code" :value="old('access_code')" required autocomplete="off" />
                <p class="mt-1 text-xs text-slate-500">Garde ce code privé si tu mets l’app en ligne.</p>
                <x-input-error :messages="$errors->get('access_code')" class="mt-2" />
            </div>
        @endif

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <p class="mt-1 text-xs text-slate-500">8 caractères minimum.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between gap-3 pt-2">
            <a class="text-sm font-semibold text-slate-600 underline decoration-teal-300 underline-offset-4 hover:text-slate-950" href="{{ route('login') }}">
                Déjà inscrit ?
            </a>

            <x-primary-button>
                Créer mon espace
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

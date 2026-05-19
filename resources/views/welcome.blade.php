<x-guest-layout>
    <div class="space-y-5">
        <div>
            <p class="text-sm font-black uppercase text-teal-700">ImmoRadar</p>
            <h1 class="mt-2 text-2xl font-black text-slate-950">Assistant de décision immobilière anti-coup de cœur.</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">Ajoute tes biens, prépare tes visites, estime le coût réel et compare avant de décider.</p>
        </div>
        <div class="grid gap-3">
            <a href="{{ route('login') }}" class="ir-action-primary w-full">Connexion</a>
            <a href="{{ route('register') }}" class="ir-action-secondary w-full">Créer un compte</a>
        </div>
    </div>
</x-guest-layout>

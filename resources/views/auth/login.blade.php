<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-playfair text-3xl font-bold text-slate-900 mb-1">Bon retour !</h1>
        <p class="text-slate-500 text-sm">Connectez-vous pour continuer</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="input" placeholder="votre@email.com" required autofocus autocomplete="username">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-slate-700">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-800 transition-colors">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password"
                class="input" placeholder="••••••••" required autocomplete="current-password">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <label for="remember_me" class="text-sm text-slate-600">Se souvenir de moi</label>
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Se connecter
        </button>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-slate-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                Créer un compte
            </a>
        </p>
    @endif
</x-guest-layout>

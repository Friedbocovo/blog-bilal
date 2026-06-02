<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-playfair text-3xl font-bold text-slate-900 mb-1">Créer un compte</h1>
        <p class="text-slate-500 text-sm">Rejoignez la communauté du blog</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    class="input" placeholder="John Doe" required autofocus autocomplete="name">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">@</span>
                    <input id="username" type="text" name="username" value="{{ old('username') }}"
                        class="input pl-7" placeholder="johndoe" required autocomplete="username" pattern="[a-zA-Z0-9_]+">
                </div>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="input" placeholder="votre@email.com" required autocomplete="email">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Mot de passe</label>
            <input id="password" type="password" name="password"
                class="input" placeholder="Minimum 8 caractères" required autocomplete="new-password">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="input" placeholder="••••••••" required autocomplete="new-password">
            @error('password_confirmation')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Créer mon compte
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Déjà un compte ?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
            Se connecter
        </a>
    </p>
</x-guest-layout>

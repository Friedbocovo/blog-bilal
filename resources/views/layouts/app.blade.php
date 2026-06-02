<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Blog de Bilal')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        :root {
            --accent: #6366f1;
            --accent-dark: #4f46e5;
            --ink: #0f172a;
            --muted: #64748b;
            --surface: #f8fafc;
            --card: #ffffff;
            --border: #e2e8f0;
        }
        body { font-family: 'Inter', sans-serif; background: var(--surface); color: var(--ink); }
        .font-playfair { font-family: 'Playfair Display', serif; }
        [x-cloak] { display: none !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }

        /* Prose */
        .prose p { margin-bottom: 1.25rem; line-height: 1.8; }
        .prose h2 { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; margin: 2rem 0 1rem; color: var(--ink); }
        .prose h3 { font-size: 1.25rem; font-weight: 600; margin: 1.5rem 0 0.75rem; }
        .prose a { color: var(--accent); text-decoration: underline; }
        .prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .prose li { margin-bottom: 0.5rem; line-height: 1.7; }
        .prose blockquote { border-left: 4px solid var(--accent); padding-left: 1rem; color: var(--muted); font-style: italic; margin: 1.5rem 0; }

        /* Mention */
        .mention { color: var(--accent); font-weight: 600; }

        /* Nav active */
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: var(--accent); transition: width 0.3s ease; }
        .nav-link:hover::after { width: 100%; }

        /* Cards */
        .post-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .post-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; transition: all 0.2s ease; cursor: pointer; }
        .btn-primary { background: var(--accent); color: white; }
        .btn-primary:hover { background: var(--accent-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
        .btn-outline { border: 1.5px solid var(--border); color: var(--ink); background: white; }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }

        /* Inputs */
        .input { width: 100%; padding: 0.625rem 0.875rem; border: 1.5px solid var(--border); border-radius: 0.5rem; font-size: 0.875rem; outline: none; transition: border-color 0.2s; background: white; }
        .input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }

        /* Badge */
        .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-gray { background: #f1f5f9; color: #64748b; }
        .badge-accent { background: #ede9fe; color: #7c3aed; }

        /* Toast */
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(120%); opacity: 0; } }
        .toast { animation: slideIn 0.4s ease; }
        .toast.hide { animation: slideOut 0.4s ease forwards; }

        /* Mobile menu */
        .mobile-nav { transition: max-height 0.3s ease, opacity 0.3s ease; }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm" x-data="{ open: false }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </div>
                <span class="font-playfair text-xl font-bold text-slate-900">Bilal's Blog</span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="nav-link text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Accueil</a>
                @auth
                    <a href="{{ route('notifications.index') }}" class="relative text-slate-600 hover:text-slate-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php
                            try { $unreadCount = auth()->user()->unreadNotifications->count(); }
                            catch (\Exception $e) { $unreadCount = 0; }
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <!-- User dropdown -->
                    <div class="relative" x-data="{ userOpen: false }" @click.away="userOpen = false">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 focus:outline-none">
                            <img src="{{ auth()->user()->getAvatarUrl(36) }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full ring-2 ring-indigo-100 hover:ring-indigo-300 transition-all">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        <div x-show="userOpen" x-cloak x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Mon profil
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.posts.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                    Dashboard admin
                                </a>
                            @endif
                            <div class="border-t border-slate-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm">S'inscrire</a>
                @endauth
            </div>

            <!-- Mobile burger -->
            <button @click="open = !open" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Mobile Nav -->
        <div x-show="open" x-cloak class="md:hidden pb-4 border-t border-slate-100 pt-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Accueil</a>
            @auth
                <a href="{{ route('notifications.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Notifications</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Mon profil</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.posts.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Dashboard admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="px-3">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 text-sm font-medium text-red-600">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">Connexion</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg">S'inscrire</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Toast Notifications -->
@if(session('success'))
<div id="toast" class="toast fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-green-200 text-green-800 px-5 py-4 rounded-xl shadow-xl max-w-sm">
    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
    </div>
    <p class="text-sm font-medium">{{ session('success') }}</p>
</div>
<script>setTimeout(() => { const t = document.getElementById('toast'); if(t){ t.classList.add('hide'); setTimeout(()=>t.remove(),400); } }, 4000);</script>
@endif

@if($errors->any())
<div id="toast-err" class="toast fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-white border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-xl max-w-sm">
    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/></svg>
    </div>
    <p class="text-sm font-medium">{{ $errors->first() }}</p>
</div>
<script>setTimeout(() => { const t = document.getElementById('toast-err'); if(t){ t.classList.add('hide'); setTimeout(()=>t.remove(),400); } }, 5000);</script>
@endif

<!-- Main Content -->
<main class="min-h-screen">
    @yield('content')
    {{ $slot ?? '' }}
</main>

<!-- Footer -->
<footer class="mt-16 bg-slate-900 text-slate-300">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                    </div>
                    <span class="font-playfair text-lg font-bold text-white">Bilal's Blog</span>
                </div>
                <p class="text-sm text-slate-400">Étudiant en Génie Informatique & Télécommunications — EPAC</p>
            </div>
            <div class="flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Accueil</a>
                @auth
                    <a href="{{ route('profile.edit') }}" class="hover:text-white transition-colors">Profil</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors">Connexion</a>
                @endauth
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-slate-800 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} BORO N'GOBI BILAL. Tous droits réservés.
        </div>
    </div>
</footer>

</body>
</html>

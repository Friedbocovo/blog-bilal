<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Blog')</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            :root {
                --ink: #1a1a2e;
                --cream: #f8f6f0;
                --accent: #c9440e;
                --muted: #6b6b7b;
            }
            
            * {
                color-scheme: light dark;
            }
            
            .font-playfair {
                font-family: 'Playfair Display', serif;
            }
            
            .font-sans {
                font-family: 'DM Sans', sans-serif;
            }
            
            body {
                background-color: var(--cream);
                color: var(--ink);
            }
            
            .btn-primary {
                background-color: var(--accent);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.375rem;
                transition: opacity 0.2s;
            }
            
            .btn-primary:hover {
                opacity: 0.9;
            }
            
            .text-accent {
                color: var(--accent);
            }
            
            .mention {
                color: var(--accent);
                font-weight: 500;
            }

            .btn-primary {
                transition: transform 0.25s ease, box-shadow 0.25s ease, opacity 0.25s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            }

            a {
                transition: color 0.25s ease;
            }

            a:hover {
                color: var(--accent);
            }

            img,
            video,
            .card,
            .content-card {
                transition: transform 0.35s ease, box-shadow 0.35s ease, opacity 0.35s ease;
            }

            .card:hover,
            .content-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.12);
            }

            .animate-fade-in {
                animation: fadeInUp 0.6s ease both;
            }

            [x-cloak] {
                display: none !important;
            }

            .animate-page-enter {
                animation: pageFade 0.45s ease both;
            }

            .page-overlay {
                background: rgba(15, 23, 42, 0.88);
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(12px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pageFade {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            html {
                scroll-behavior: smooth;
            }
        </style>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased" style="background-color: var(--cream); color: var(--ink);">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="font-playfair text-2xl font-bold" style="color: var(--ink);">
                        Blog
                    </a>

                    <!-- Right side nav -->
                    <div class="flex items-center gap-4">
                        @auth
                            <!-- Notifications -->
                            <a href="{{ route('notifications.index') }}" class="relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php
                                    try {
                                        $unreadCount = auth()->user()->unreadNotifications->count();
                                    } catch (\Exception $e) {
                                        $unreadCount = 0;
                                    }
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>

                            <!-- Avatar Dropdown -->
                            <div class="relative group">
                                <img src="{{ auth()->user()->getAvatarUrl(40) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full cursor-pointer">
                                
                                <div class="absolute right-0 mt-0 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.posts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard admin</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}?redirect={{ urlencode(url()->full()) }}" class="text-gray-700 hover:text-gray-900">Connexion</a>
                            <a href="{{ route('register') }}?redirect={{ urlencode(url()->full()) }}" class="btn-primary">S'inscrire</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg animate-bounce" style="animation: slideIn 0.3s ease-in;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg" style="animation: slideIn 0.3s ease-in;">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Main Content -->
        <main class="min-h-screen">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <p class="font-playfair text-2xl font-bold mb-2">Blog</p>
                    <p class="text-gray-400">&copy; {{ date('Y') }} Tous droits réservés.</p>
                </div>
            </div>
        </footer>

        <style>
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        </style>
    </body>
</html>


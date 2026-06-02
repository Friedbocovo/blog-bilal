<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Blog de Bilal') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .input { width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:0.5rem; font-size:0.875rem; outline:none; transition:border-color 0.2s; background:white; }
        .input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }
    </style>
</head>
<body class="antialiased bg-slate-50 min-h-screen flex">

    <!-- Left panel (decorative) -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 flex-col justify-between p-12 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 30% 40%, #a5b4fc 0%, transparent 50%), radial-gradient(circle at 70% 80%, #c4b5fd 0%, transparent 40%);"></div>
        <div class="absolute bottom-0 left-0 right-0 h-64 opacity-5" style="background: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 40px);"></div>

        <!-- Logo -->
        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                </div>
                <span class="font-playfair text-2xl font-bold text-white">Bilal's Blog</span>
            </a>
        </div>

        <!-- Quote -->
        <div class="relative">
            <blockquote class="text-white">
                <p class="font-playfair text-3xl font-bold leading-snug mb-4">
                    "Partager le savoir,<br>c'est le multiplier."
                </p>
                <footer class="text-indigo-300 text-sm">
                    — BORO N'GOBI BILAL<br>
                    <span class="text-indigo-400 text-xs">Étudiant en Génie Informatique & Télécoms — EPAC</span>
                </footer>
            </blockquote>
        </div>

        <!-- Bottom dots decoration -->
        <div class="relative flex gap-2">
            <div class="w-2 h-2 bg-white/40 rounded-full"></div>
            <div class="w-2 h-2 bg-white/60 rounded-full"></div>
            <div class="w-2 h-2 bg-white/80 rounded-full"></div>
        </div>
    </div>

    <!-- Right panel (form) -->
    <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-16">
        <!-- Mobile logo -->
        <div class="lg:hidden mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                </div>
                <span class="font-playfair text-xl font-bold text-slate-900">Bilal's Blog</span>
            </a>
        </div>

        <div class="w-full max-w-md fade-up">
            {{ $slot }}
        </div>
    </div>

</body>
</html>

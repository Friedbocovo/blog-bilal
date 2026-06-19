@extends('layouts.app')

@section('title', 'Blog de Bilal — Accueil')

@section('content')

<!-- Hero -->
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 50%, #6366f1 0%, transparent 50%), radial-gradient(circle at 80% 20%, #8b5cf6 0%, transparent 40%);"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-20 md:py-28 text-center">
       
        <h1 class="font-playfair text-4xl md:text-6xl lg:text-7xl font-black text-white mb-6 fade-up" style="animation-delay: 0.1s; line-height: 1.1;">
            Bienvenue sur<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">mon Blog</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-300 max-w-2xl mx-auto mb-8 fade-up" style="animation-delay: 0.2s;">
            Je m'appelle <strong class="text-white">BORO N'GOBI BILAL</strong>. Je partage ici mes articles, expériences et réflexions autour de l'informatique et de la tech.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center fade-up" style="animation-delay: 0.3s;">
            <a href="#posts" class="btn btn-primary px-6 py-3 text-base">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                Lire les articles
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline bg-white/10 border-white/20 text-white hover:bg-white/20 px-6 py-3 text-base">
                Rejoindre la communauté
            </a>
            @endguest
        </div>

        <!-- Stats -->
        <div class="flex justify-center gap-8 mt-12 fade-up" style="animation-delay: 0.4s;">
            <div class="text-center">
                <p class="text-2xl font-bold text-white">{{ $posts->total() }}</p>
                <p class="text-xs text-slate-400 mt-1">Articles</p>
            </div>
            <div class="w-px bg-slate-700"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white">∞</p>
                <p class="text-xs text-slate-400 mt-1">Idées</p>
            </div>
            <div class="w-px bg-slate-700"></div>
            <div class="text-center">
                <p class="text-2xl font-bold text-white">100%</p>
                <p class="text-xs text-slate-400 mt-1">Passion</p>
            </div>
        </div>
    </div>

    <!-- Wave -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 60L1440 60L1440 0C1440 0 1200 40 720 40C240 40 0 0 0 0L0 60Z" fill="#f8fafc"/>
        </svg>
    </div>
</section>

<!-- Posts -->
<section id="posts" class="max-w-6xl mx-auto px-4 sm:px-6 py-16">

    @if($posts->isEmpty())
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-xl font-semibold text-slate-700 mb-2">Aucun article pour le moment</h3>
            <p class="text-slate-500">Revenez bientôt pour découvrir le premier article.</p>
        </div>
    @else

    <!-- Featured post (first one) -->
    @php $featured = $posts->first(); @endphp
    <div class="mb-12">
        <div class="flex items-center gap-2 mb-6">
            <span class="w-8 h-0.5 bg-indigo-500 rounded"></span>
            <span class="text-sm font-semibold text-indigo-600 uppercase tracking-wider">À la une</span>
        </div>
        <article class="post-card group grid md:grid-cols-2 gap-0 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="relative overflow-hidden bg-slate-100 h-64 md:h-auto">
                @if($featured->cover_image)
                    <img src="{{ $featured->cover_image }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/30" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                    </div>
                @endif
            </div>
            <div class="p-8 md:p-10 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ $featured->author->getAvatarUrl(32) }}" alt="{{ $featured->author->name }}" class="w-8 h-8 rounded-full">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">{{ $featured->author->name }}</p>
                        <p class="text-xs text-slate-400">{{ $featured->published_at->diffForHumans() }}</p>
                    </div>
                </div>
                <h2 class="font-playfair text-2xl md:text-3xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors leading-tight">
                    <a href="{{ route('posts.show', $featured->slug) }}">{{ $featured->title }}</a>
                </h2>
                @if($featured->excerpt)
                    <p class="text-slate-500 mb-6 leading-relaxed">{{ $featured->excerpt }}</p>
                @endif
                <div class="flex items-center justify-between">
                    <a href="{{ route('posts.show', $featured->slug) }}" class="btn btn-primary">
                        Lire l'article
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <span class="text-sm text-slate-400 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $featured->all_comments_count ?? 0 }}
                    </span>
                </div>
            </div>
        </article>
    </div>

    <!-- Other posts grid -->
    @if($posts->count() > 1)
    <div class="flex items-center gap-2 mb-6">
        <span class="w-8 h-0.5 bg-indigo-500 rounded"></span>
        <span class="text-sm font-semibold text-indigo-600 uppercase tracking-wider">Tous les articles</span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @foreach($posts->skip(1) as $post)
        <article class="post-card group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="relative overflow-hidden h-48 bg-slate-100">
                @if($post->cover_image)
                    <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                    </div>
                @endif
            </div>
            <div class="p-5 flex flex-col flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ $post->author->getAvatarUrl(24) }}" alt="{{ $post->author->name }}" class="w-6 h-6 rounded-full">
                    <span class="text-xs text-slate-500">{{ $post->author->name }}</span>
                    <span class="text-xs text-slate-300">·</span>
                    <span class="text-xs text-slate-400">{{ $post->published_at->diffForHumans() }}</span>
                </div>
                <h3 class="font-playfair text-lg font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors leading-snug flex-1">
                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                </h3>
                @if($post->excerpt)
                    <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $post->excerpt }}</p>
                @endif
                <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-auto">
                    <a href="{{ route('posts.show', $post->slug) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors">
                        Lire
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <span class="text-xs text-slate-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $post->all_comments_count ?? 0 }}
                    </span>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @endif

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $posts->links() }}
    </div>

    @endif
</section>

@endsection

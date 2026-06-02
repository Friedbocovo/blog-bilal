@extends('layouts.app')

@section('title', $post->title . ' — Blog de Bilal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 fade-up">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Accueil</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-600 truncate max-w-xs">{{ $post->title }}</span>
    </nav>

    <!-- Cover Image -->
    @if($post->cover_image)
        <div class="relative overflow-hidden rounded-2xl mb-8 shadow-lg h-72 md:h-96">
            <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
    @endif

    <!-- Article -->
    <article class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 sm:p-10">

            <!-- Meta -->
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <img src="{{ $post->author->getAvatarUrl(44) }}" alt="{{ $post->author->name }}" class="w-11 h-11 rounded-full ring-2 ring-indigo-100">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">{{ $post->author->name }}</p>
                    <p class="text-xs text-slate-400">{{ $post->published_at->format('j F Y') }}</p>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    <span class="badge badge-accent">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        {{ $post->allComments->count() }} commentaires
                    </span>
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-outline text-xs px-3 py-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Supprimer cet article ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger text-xs px-3 py-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Title -->
            <h1 class="font-playfair text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 mb-6 leading-tight">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="text-lg text-slate-500 border-l-4 border-indigo-300 pl-4 mb-8 italic">{{ $post->excerpt }}</p>
            @endif

            <!-- Media Gallery -->
            @if($post->media && count($post->media) > 0)
                <div class="mb-8" x-data="{ lightbox: false, current: 0, items: {{ json_encode(array_map(fn($m) => ['src' => asset('storage/'.$m), 'type' => str_ends_with($m, '.mp4') || str_ends_with($m, '.webm') ? 'video' : 'image'], $post->media)) }} }">
                    <div class="grid grid-cols-2 {{ count($post->media) > 2 ? 'md:grid-cols-3' : '' }} gap-3 rounded-xl overflow-hidden">
                        @foreach($post->media as $index => $media)
                            @php $isVideo = str_ends_with($media, '.mp4') || str_ends_with($media, '.webm'); @endphp
                            <button @click="lightbox = true; current = {{ $index }}"
                                class="group relative overflow-hidden rounded-xl bg-slate-100 aspect-video focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @if($isVideo)
                                    <video muted autoplay loop class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <source src="{{ asset('storage/' . $media) }}" type="video/{{ pathinfo($media, PATHINFO_EXTENSION) }}">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 bg-black/50 backdrop-blur rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ asset('storage/' . $media) }}" alt="Media {{ $index+1 }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Lightbox -->
                    <div x-show="lightbox" x-cloak @keydown.window.escape="lightbox = false"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 backdrop-blur-sm">
                        <button @click="lightbox = false" class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <template x-if="items[current] && items[current].type === 'image'">
                            <img :src="items[current].src" class="max-w-full max-h-[85vh] rounded-xl object-contain shadow-2xl">
                        </template>
                        <template x-if="items[current] && items[current].type === 'video'">
                            <video :src="items[current].src" controls autoplay class="max-w-full max-h-[85vh] rounded-xl shadow-2xl"></video>
                        </template>
                        <template x-if="items.length > 1">
                            <div>
                                <button @click="current = (current - 1 + items.length) % items.length"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="current = (current + 1) % items.length"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="prose prose-lg max-w-none text-slate-700">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </article>

    <!-- Comments -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 sm:px-10 py-6 border-b border-slate-100">
            <h2 class="font-playfair text-2xl font-bold text-slate-900">
                Commentaires
                <span class="ml-2 text-lg font-normal text-slate-400">({{ $post->allComments->count() }})</span>
            </h2>
        </div>

        <div class="px-6 sm:px-10 py-6">
            @auth
                <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-8">
                    @csrf
                    <div class="flex gap-3">
                        <img src="{{ auth()->user()->getAvatarUrl(40) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full flex-shrink-0 mt-1">
                        <div class="flex-1">
                            <textarea name="body" rows="3" placeholder="Partagez votre avis... Utilisez @username pour mentionner quelqu'un"
                                class="input resize-none mb-3" required>{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="btn btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Publier
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="flex items-center gap-4 p-5 bg-indigo-50 rounded-xl mb-8">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-800">Connectez-vous pour laisser un commentaire</p>
                        <p class="text-xs text-slate-500 mt-0.5">Rejoignez la discussion !</p>
                    </div>
                    <a href="{{ route('login') }}?redirect={{ urlencode(url()->full()) }}" class="btn btn-primary text-sm">Se connecter</a>
                </div>
            @endauth

            <div class="space-y-1">
                @forelse($post->comments as $comment)
                    @include('comments._comment', ['comment' => $comment])
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <p class="text-sm">Aucun commentaire. Soyez le premier !</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

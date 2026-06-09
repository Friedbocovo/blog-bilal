@extends('layouts.app')

@section('title', 'Commentaires — Admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-playfair text-3xl font-bold text-slate-900">Commentaires</h1>
            <p class="text-slate-500 mt-1 text-sm">Gérez et répondez aux commentaires</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour aux articles
        </a>
    </div>

    <!-- Search -->
    <form method="GET" class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Rechercher dans les commentaires..."
            class="input flex-1">
        <button type="submit" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Rechercher
        </button>
        @if(request('search'))
            <a href="{{ route('admin.comments.index') }}" class="btn btn-outline">Effacer</a>
        @endif
    </form>

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" id="comment-{{ $comment->id }}">
            <div class="p-5 sm:p-6">
                <!-- Header -->
                <div class="flex flex-wrap items-start gap-3 mb-4">
                    <img src="{{ $comment->author->getAvatarUrl(40) }}" alt="{{ $comment->author->name }}"
                        class="w-10 h-10 rounded-full flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-semibold text-slate-900 text-sm">{{ $comment->author->name }}</span>
                            @if($comment->author->isAdmin())
                                <span class="badge badge-accent text-xs">Admin</span>
                            @endif
                            @if($comment->parent_id)
                                <span class="badge badge-gray text-xs">Réponse</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-0.5">
                            <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                            <span class="text-slate-300">·</span>
                            <a href="{{ route('posts.show', $comment->post->slug) }}#comment-{{ $comment->id }}"
                                target="_blank"
                                class="text-xs text-indigo-600 hover:text-indigo-800 transition-colors truncate max-w-xs">
                                {{ $comment->post->title }}
                            </a>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 ml-auto">
                        <a href="{{ route('posts.show', $comment->post->slug) }}#comment-{{ $comment->id }}"
                            target="_blank"
                            class="btn btn-outline text-xs px-3 py-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Voir
                        </a>
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                            onsubmit="return confirm('Supprimer ce commentaire ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger text-xs px-3 py-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Body -->
                @if($comment->parent)
                    <div class="bg-slate-50 rounded-lg px-3 py-2 mb-3 text-xs text-slate-500 border-l-2 border-slate-200">
                        <span class="font-medium">En réponse à {{ $comment->parent->author->name }} :</span>
                        "{{ Str::limit($comment->parent->body, 80) }}"
                    </div>
                @endif
                <p class="text-slate-700 text-sm leading-relaxed bg-slate-50 rounded-lg px-4 py-3">
                    {{ $comment->body }}
                </p>

                <!-- Reply form -->
                <div x-data="{ open: false }" class="mt-4">
                    <button @click="open = !open"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        <span x-text="open ? 'Annuler' : 'Répondre'"></span>
                    </button>

                    <div x-show="open" x-cloak x-transition class="mt-3">
                        <form method="POST" action="{{ route('admin.comments.reply', $comment) }}">
                            @csrf
                            <textarea name="body" rows="3"
                                placeholder="Votre réponse en tant qu'admin..."
                                class="input resize-none mb-2 text-sm" required></textarea>
                            <button type="submit" class="btn btn-primary text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Publier la réponse
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-slate-100">
            <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <p class="text-slate-500 font-medium">Aucun commentaire</p>
        </div>
        @endforelse
    </div>

    @if($comments->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $comments->links() }}
    </div>
    @endif
</div>
@endsection

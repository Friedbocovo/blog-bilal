@extends('layouts.app')

@section('title', $user->name . ' — Profil')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <img src="{{ $user->getAvatarUrl(120) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full shadow-lg">

            <div class="flex-1">
                <h1 class="font-playfair text-4xl font-bold mb-2">{{ $user->name }}</h1>
                <p class="text-gray-600 text-sm mb-4">@{{ $user->username }}</p>
                <p class="text-gray-700 leading-relaxed">{{ $user->bio ?? 'Aucune biographie renseignée.' }}</p>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500">Articles publiés</p>
                        <p class="mt-2 text-xl font-bold text-gray-900">{{ $user->posts()->published()->count() }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500">Membre depuis</p>
                        <p class="mt-2 text-xl font-bold text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-500">Rôle</p>
                        <p class="mt-2 text-xl font-bold text-gray-900">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="font-playfair text-3xl font-bold mb-6">Articles publiés</h2>

        @if($posts->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($posts as $post)
                    <article class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-xl transition-shadow">
                        @if($post->cover_image)
                            <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-44 object-cover">
                        @else
                            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-4xl">📝</div>
                        @endif
                        <div class="p-6">
                            <h3 class="font-playfair text-2xl font-bold mb-2">
                                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-accent">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-600 mb-4">{{ $post->excerpt ?? \Illuminate\Support\Str::limit($post->content, 120) }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $post->published_at->format('d M Y') }}</span>
                                <span>💬 {{ $post->allComments()->count() }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-gray-600">Cet utilisateur n'a encore publié aucun article.</p>
        @endif
    </div>
</div>
@endsection

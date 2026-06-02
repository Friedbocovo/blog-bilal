@extends('layouts.app')

@section('title', 'Blog - Accueil')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 md:mt-12">
    <!-- Hero Section -->
    <div class="py-12 md:py-20 text-center animate-fade-in" style="background: linear-gradient(135deg, #1a1a2e 0%, #2d2d4a 100%); color: white; border-radius: 0.5rem; margin-bottom: 2rem;">
        <h1 class="font-playfair text-4xl md:text-6xl font-bold mb-4">Bienvenue sur mon Blog</h1>
                <p class="text-lg md:text-2xl text-gray-300">Je m'appelle BORO N'GOBI BILAL , je suis étudiant en <br /> Génie informatique et télécommunications à L'EPAC</p>
<p>-----------</p>
        <p class="text-lg md:text-xl text-gray-300">Découvrez mes articles, pensées et expériences</p>
    </div>

    <!-- Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        @forelse($posts as $post)
            <article class="card bg-white rounded-lg shadow-lg overflow-hidden transition duration-500 hover:-translate-y-1 hover:shadow-2xl">
                <!-- Cover Image -->
                @if($post->cover_image)
                    <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-4xl">
                        📝
                    </div>
                @endif

                <div class="p-6">
                    <!-- Author Info -->
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $post->author->getAvatarUrl(32) }}" alt="{{ $post->author->name }}" class="w-8 h-8 rounded-full">
                        <div class="text-sm">
                            <p class="font-semibold text-gray-900">{{ $post->author->name }}</p>
                            <p class="text-gray-500">{{ $post->published_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="font-playfair text-2xl font-bold mb-3 text-gray-900">
                        <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-accent transition-colors">
                            {{ $post->title }}
                        </a>
                    </h2>

                    <!-- Excerpt -->
                    @if($post->excerpt)
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                    @endif

                    <!-- Read More & Comments -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn-primary text-sm">
                            Lire l'article
                        </a>
                        <span class="text-sm text-gray-500">
                            💬 {{ $post->all_comments_count ?? 0 }} commentaires
                        </span>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-xl text-gray-600">Aucun article publié pour le moment.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mb-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection

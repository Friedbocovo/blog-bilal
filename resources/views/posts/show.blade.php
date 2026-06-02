@extends('layouts.app')

@section('title', $post->title . ' - Blog')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-page-enter">
    <!-- Cover Image -->
    @if($post->cover_image)
        <img src="{{ asset('storage/' . $post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg mb-8 shadow-lg">
    @endif

    <!-- Article Header -->
    <article class="bg-white rounded-lg p-8 shadow-lg mb-8">
        <h1 class="font-playfair text-4xl md:text-5xl font-bold mb-4">{{ $post->title }}</h1>

        <!-- Meta Info -->
        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-200">
            <img src="{{ $post->author->getAvatarUrl(48) }}" alt="{{ $post->author->name }}" class="w-12 h-12 rounded-full">
            <div>
                <p class="font-semibold text-gray-900">{{ $post->author->name }}</p>
                <p class="text-gray-500">{{ $post->published_at->format('j F Y') }}</p>
            </div>

            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn-primary text-sm">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary text-sm bg-red-600 hover:bg-red-700" onclick="return confirm('Êtes-vous sûr ?')">
                            Supprimer
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Content -->
        @if($post->media && count($post->media) > 0)
            <div class="mb-8 animate-fade-in">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($post->media as $index => $media)
                        @php
                            $isVideo = \Illuminate\Support\Str::endsWith($media, ['.mp4', '.webm']);
                            $mediaUrl = asset('storage/' . $media);
                        @endphp
                        <button type="button" class="js-media-thumb group relative overflow-hidden rounded-3xl bg-slate-950 shadow-2xl border border-slate-800 focus:outline-none focus:ring-2 focus:ring-accent" data-index="{{ $index }}" data-type="{{ $isVideo ? 'video' : 'image' }}" data-src="{{ $mediaUrl }}">
                            <div class="relative h-72 md:h-96 w-full">
                                @if($isVideo)
                                    <video muted autoplay loop class="h-full w-full object-cover opacity-80 transition duration-300 group-hover:scale-105">
                                        <source src="{{ $mediaUrl }}" type="video/{{ pathinfo($media, PATHINFO_EXTENSION) }}">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center text-white text-4xl">▶</div>
                                @else
                                    <img src="{{ $mediaUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>

                <div id="mediaLightbox" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 p-4 backdrop-blur-sm">
                    <div class="absolute inset-0" id="lightboxBackdrop"></div>
                    <div class="relative w-full max-w-5xl max-h-[90vh] overflow-hidden rounded-[32px] bg-slate-950 shadow-2xl">
                        <button type="button" id="lightboxClose" class="absolute top-4 right-4 z-20 rounded-full bg-slate-900/80 p-3 text-white transition hover:bg-slate-900">✕</button>
                        <button type="button" id="lightboxPrev" class="hidden absolute left-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-slate-900/70 p-3 text-white transition hover:bg-slate-900">◀</button>
                        <button type="button" id="lightboxNext" class="hidden absolute right-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-slate-900/70 p-3 text-white transition hover:bg-slate-900">▶</button>
                        <div class="flex h-[85vh] items-center justify-center overflow-hidden bg-black">
                            <img id="lightboxImage" class="hidden h-full max-h-[85vh] w-full object-contain" alt="Media agrandi">
                            <video id="lightboxVideo" class="hidden h-full max-h-[85vh] w-full object-contain" controls></video>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    function initMediaLightbox() {
                        const thumbs = Array.from(document.querySelectorAll('.js-media-thumb'));
                        if (!thumbs.length) {
                            return;
                        }

                        const lightbox = document.getElementById('mediaLightbox');
                        const backdrop = document.getElementById('lightboxBackdrop');
                        const lightboxImage = document.getElementById('lightboxImage');
                        const lightboxVideo = document.getElementById('lightboxVideo');
                        const prevButton = document.getElementById('lightboxPrev');
                        const nextButton = document.getElementById('lightboxNext');
                        const closeButton = document.getElementById('lightboxClose');

                        const mediaItems = thumbs.map(thumb => ({
                            src: thumb.dataset.src,
                            type: thumb.dataset.type,
                        }));

                        let currentIndex = -1;

                        function showLightbox(index) {
                            currentIndex = (index + mediaItems.length) % mediaItems.length;
                            const item = mediaItems[currentIndex];
                            if (!item) {
                                return;
                            }

                            if (item.type === 'video') {
                                lightboxImage.classList.add('hidden');
                                lightboxVideo.src = item.src;
                                lightboxVideo.classList.remove('hidden');
                                lightboxVideo.play();
                            } else {
                                lightboxVideo.classList.add('hidden');
                                lightboxVideo.pause();
                                lightboxVideo.removeAttribute('src');
                                lightboxImage.src = item.src;
                                lightboxImage.classList.remove('hidden');
                            }

                            if (mediaItems.length > 1) {
                                prevButton.classList.remove('hidden');
                                nextButton.classList.remove('hidden');
                            }

                            lightbox.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }

                        function hideLightbox() {
                            lightbox.classList.add('hidden');
                            lightboxImage.classList.add('hidden');
                            lightboxVideo.classList.add('hidden');
                            lightboxVideo.pause();
                            lightboxVideo.removeAttribute('src');
                            currentIndex = -1;
                            document.body.style.overflow = '';
                        }

                        function goTo(index) {
                            if (mediaItems.length < 2) {
                                return;
                            }
                            showLightbox(index);
                        }

                        thumbs.forEach((thumb, index) => {
                            thumb.addEventListener('click', () => showLightbox(index));
                        });

                        closeButton.addEventListener('click', hideLightbox);
                        backdrop.addEventListener('click', hideLightbox);

                        prevButton.addEventListener('click', (event) => {
                            event.stopPropagation();
                            goTo(currentIndex - 1);
                        });

                        nextButton.addEventListener('click', (event) => {
                            event.stopPropagation();
                            goTo(currentIndex + 1);
                        });

                        document.addEventListener('keydown', (event) => {
                            if (currentIndex < 0) {
                                return;
                            }
                            if (event.key === 'Escape') {
                                hideLightbox();
                            } else if (event.key === 'ArrowRight' && mediaItems.length > 1) {
                                goTo(currentIndex + 1);
                            } else if (event.key === 'ArrowLeft' && mediaItems.length > 1) {
                                goTo(currentIndex - 1);
                            }
                        });
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initMediaLightbox);
                    } else {
                        initMediaLightbox();
                    }
                })();
            </script>
        @endif

        <div class="prose prose-lg max-w-none mb-8">
            {!! nl2br(e($post->content)) !!}
        </div>
    </article>

    <!-- Comments Section -->
    <section class="bg-white rounded-lg p-8 shadow-lg">
        <h2 class="font-playfair text-3xl font-bold mb-6">
            Commentaires ({{ $post->allComments->count() }})
        </h2>

        @auth
            <!-- Comment Form -->
            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-8 p-6 bg-gray-50 rounded-lg">
                @csrf
                <textarea name="body" rows="4" placeholder="Votre commentaire..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: var(--accent); focus:ring-color: var(--accent);" required></textarea>
                @error('body')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-primary mt-4">Publier le commentaire</button>
            </form>
        @else
            <div class="mb-8 p-6 bg-gray-50 rounded-lg text-center">
                <p class="text-gray-600 mb-4">Connectez-vous pour laisser un commentaire.</p>
                <a href="{{ route('login') }}?redirect={{ urlencode(url()->full()) }}" class="btn-primary">Se connecter</a>
            </div>
        @endauth

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($post->comments as $comment)
                @include('comments._comment', ['comment' => $comment])
            @empty
                <p class="text-gray-500 text-center py-8">Aucun commentaire pour le moment.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

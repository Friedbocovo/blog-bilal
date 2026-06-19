@extends('layouts.app')

@section('title', 'Modifier — ' . $post->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.posts.index') }}" class="w-9 h-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="font-playfair text-2xl font-bold text-slate-900">Modifier l'article</h1>
            <p class="text-sm text-slate-500 mt-0.5 truncate max-w-xs">{{ $post->title }}</p>
        </div>
        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="ml-auto btn btn-outline text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Voir
        </a>
    </div>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" class="input text-base" required value="{{ old('title', $post->title) }}">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="excerpt" class="block text-sm font-semibold text-slate-700 mb-1.5">Extrait <span class="text-slate-400 font-normal">(affiché sur la liste)</span></label>
                <textarea name="excerpt" id="excerpt" rows="2" class="input resize-none">{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-1.5">Contenu <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="14" class="input resize-y font-mono text-sm" required>{{ old('content', $post->content) }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
            <h2 class="font-semibold text-slate-800">Médias</h2>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Image de couverture actuelle</label>
                @if($post->cover_image)
                    <div class="relative inline-block mb-3">
                        <img src="{{ $post->cover_image }}" alt="" class="w-40 h-28 object-cover rounded-xl border border-slate-200">
                        <span class="absolute -top-2 -right-2 badge badge-green text-xs">Actuelle</span>
                    </div>
                @else
                    <p class="text-sm text-slate-400 mb-3">Aucune image de couverture</p>
                @endif
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-indigo-300 transition-colors cursor-pointer" onclick="document.getElementById('cover_image').click()">
                    <p class="text-sm text-slate-500">{{ $post->cover_image ? 'Remplacer l\'image' : 'Ajouter une image' }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, GIF — max 2MB</p>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*" class="hidden"
                        onchange="previewImage(this)">
                </div>
                <img id="new-cover-preview" src="" alt="" class="hidden w-full h-48 object-cover rounded-xl mt-3 border border-slate-200">
                @error('cover_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            @if($post->media && count($post->media) > 0)
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Galerie actuelle</label>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3">
                    @foreach($post->media as $media)
                        @php $isVideo = str_ends_with($media, '.mp4') || str_ends_with($media, '.webm'); @endphp
                        <div class="relative aspect-square rounded-lg overflow-hidden bg-slate-100">
                            @if($isVideo)
                                <video class="w-full h-full object-cover"><source src="{{ $media }}"></video>
                            @else
                                <img src="{{ $media }}" alt="" class="w-full h-full object-cover">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ajouter des médias</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-indigo-300 transition-colors cursor-pointer" onclick="document.getElementById('media').click()">
                    <p class="text-sm text-slate-500">Sélectionner des fichiers (ajoutés aux existants)</p>
                    <p class="text-xs text-slate-400 mt-0.5">Images et vidéos — max 10MB chacun</p>
                    <input type="file" name="media[]" id="media" accept="image/*,video/*" multiple class="hidden">
                </div>
                @error('media.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
            <h2 class="font-semibold text-slate-800 mb-4">Publication</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="status" value="draft" {{ old('status', $post->status) === 'draft' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <div>
                        <span class="text-sm font-medium text-slate-800">Brouillon</span>
                        <p class="text-xs text-slate-400">Non visible par les lecteurs</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="status" value="published" {{ old('status', $post->status) === 'published' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <div>
                        <span class="text-sm font-medium text-slate-800">Publié</span>
                        <p class="text-xs text-slate-400">Visible par tous</p>
                    </div>
                </label>
            </div>
            @error('status') <p class="text-red-500 text-xs mb-4">{{ $message }}</p> @enderror

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn btn-primary px-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mettre à jour
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline px-6">Annuler</a>
            </div>
        </div>

    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('new-cover-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

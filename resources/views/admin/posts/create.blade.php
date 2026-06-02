@extends('layouts.app')

@section('title', 'Créer un article')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="font-playfair text-4xl font-bold mb-8">Créer un nouvel article</h1>

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg p-8 shadow-lg">
        @csrf

        <!-- Title -->
        <div class="mb-6">
            <label for="title" class="block font-bold mb-2">Titre</label>
            <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: #ccc;" required value="{{ old('title') }}">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Excerpt -->
        <div class="mb-6">
            <label for="excerpt" class="block font-bold mb-2">Extrait</label>
            <textarea name="excerpt" id="excerpt" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: #ccc;">{{ old('excerpt') }}</textarea>
            @error('excerpt')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div class="mb-6">
            <label for="content" class="block font-bold mb-2">Contenu</label>
            <textarea name="content" id="content" rows="12" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 font-mono" style="border-color: #ccc;" required>{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Cover Image -->
        <div class="mb-6">
            <label for="cover_image" class="block font-bold mb-2">Image de couverture</label>
            <input type="file" name="cover_image" id="cover_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg" style="border-color: #ccc;">
            @error('cover_image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Media Attachments -->
        <div class="mb-6">
            <label for="media" class="block font-bold mb-2">Médias (images ou vidéos)</label>
            <input type="file" name="media[]" id="media" accept="image/*,video/*" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg" style="border-color: #ccc;">
            <p class="text-sm text-gray-500 mt-1">Vous pouvez ajouter plusieurs images ou vidéos.</p>
            @error('media.*')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-8">
            <label class="block font-bold mb-4">Statut</label>
            <div class="flex gap-6">
                <label class="flex items-center gap-2">
                    <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}>
                    <span>Brouillon</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="status" value="published" {{ old('status') === 'published' ? 'checked' : '' }}>
                    <span>Publié</span>
                </label>
            </div>
            @error('status')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary">Créer l'article</button>
            <a href="{{ route('admin.posts.index') }}" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg">Annuler</a>
        </div>
    </form>
</div>
@endsection

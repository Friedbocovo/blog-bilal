@extends('layouts.app')

@section('title', 'Créer un article')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.posts.index') }}" class="w-9 h-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="font-playfair text-2xl font-bold text-slate-900">Nouvel article</h1>
            <p class="text-sm text-slate-500 mt-0.5">Remplissez les champs ci-dessous pour publier</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" class="input text-base" placeholder="Titre de l'article..." required value="{{ old('title') }}">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Excerpt -->
            <div>
                <label for="excerpt" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Extrait
                    <span class="text-slate-400 font-normal">(affiché sur la liste)</span>
                </label>
                <textarea name="excerpt" id="excerpt" rows="2" class="input resize-none" placeholder="Courte description de l'article...">{{ old('excerpt') }}</textarea>
                @error('excerpt') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-1.5">Contenu <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="14" class="input resize-y font-mono text-sm" placeholder="Rédigez votre article ici..." required>{{ old('content') }}</textarea>
                @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Media -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
            <h2 class="font-semibold text-slate-800">Médias</h2>

            <!-- Cover -->
            <div>
                <label for="cover_image" class="block text-sm font-semibold text-slate-700 mb-1.5">Image de couverture</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-indigo-300 transition-colors cursor-pointer" onclick="document.getElementById('cover_image').click()">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm text-slate-500">Cliquez pour choisir une image</p>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, GIF — max 2MB</p>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*" class="hidden">
                </div>
                @error('cover_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Extra Media -->
            <div>
                <label for="media" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Galerie de médias
                    <span class="text-slate-400 font-normal">(images & vidéos)</span>
                </label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-indigo-300 transition-colors cursor-pointer" onclick="document.getElementById('media').click()">
                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    <p class="text-sm text-slate-500">Sélectionner des fichiers (multiple)</p>
                    <p class="text-xs text-slate-400 mt-1">Images et vidéos — max 10MB chacun</p>
                    <input type="file" name="media[]" id="media" accept="image/*,video/*" multiple class="hidden">
                </div>
                @error('media.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Status & Submit -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
            <h2 class="font-semibold text-slate-800 mb-4">Publication</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="status" value="draft" {{ old('status', 'draft') === 'draft' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <div>
                        <span class="text-sm font-medium text-slate-800">Brouillon</span>
                        <p class="text-xs text-slate-400">Non visible par les lecteurs</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="status" value="published" {{ old('status') === 'published' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
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
                    Créer l'article
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline px-6">Annuler</a>
            </div>
        </div>

    </form>
</div>
@endsection

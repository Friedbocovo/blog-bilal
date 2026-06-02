@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="font-playfair text-4xl font-bold mb-8 text-center">Mon profil</h1>

    <!-- Avatar -->
    <div class="text-center mb-8">
        <img src="{{ auth()->user()->getAvatarUrl(120) }}" alt="{{ auth()->user()->name }}" class="w-32 h-32 rounded-full mx-auto mb-4 shadow-lg">
        <p class="text-sm text-gray-600">
            Avatar 
        </p>
    </div>

    <!-- Profile Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="bg-white rounded-lg p-8 shadow-lg">
        @csrf
        @method('PATCH')

        <!-- Avatar Upload -->
        <div class="mb-6">
            <label for="avatar" class="block font-bold mb-2">Photo de profil</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: #ccc;">
            @error('avatar')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Formats autorisés : jpeg, png, jpg, gif. Taille max 2 Mo.</p>
        </div>

        <!-- Name -->
        <div class="mb-6">
            <label for="name" class="block font-bold mb-2">Nom</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: #ccc;" required value="{{ old('name', auth()->user()->name) }}">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Username -->
        <div class="mb-6">
            <label for="username" class="block font-bold mb-2">Nom d'utilisateur</label>
            <div class="flex items-center">
                <span class="text-gray-600 mr-2">@</span>
                <input type="text" name="username" id="username" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: #ccc;" required value="{{ old('username', auth()->user()->username) }}" pattern="[a-zA-Z0-9_]+">
            </div>
            @error('username')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Lettres, chiffres et underscores uniquement</p>
        </div>

        <!-- Email (Disabled) -->
        <div class="mb-6">
            <label for="email" class="block font-bold mb-2">Email</label>
            <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" style="border-color: #ccc;" disabled value="{{ auth()->user()->email }}">
            <p class="text-xs text-gray-500 mt-1">Non modifiable</p>
        </div>

        <!-- Bio -->
        <div class="mb-8">
            <label for="bio" class="block font-bold mb-2">Biographie</label>
            <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2" style="border-color: #ccc;" maxlength="500">{{ old('bio', auth()->user()->bio) }}</textarea>
            @error('bio')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">{{ strlen(auth()->user()->bio ?? '') }}/500 caractères</p>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="submit" class="btn-primary">Mettre à jour le profil</button>
        </div>
    </form>

    <!-- Delete Account Section -->
    <div class="mt-12 pt-8 border-t border-gray-200">
        <h2 class="font-playfair text-2xl font-bold mb-4 text-red-600">Zone de danger</h2>
        
        <form method="POST" action="{{ route('profile.destroy') }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" onclick="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                Supprimer mon compte
            </button>
        </form>
    </div>
</div>
@endsection


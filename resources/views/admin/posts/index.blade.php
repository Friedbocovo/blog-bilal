@extends('layouts.app')

@section('title', 'Dashboard Admin - Articles')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="font-playfair text-4xl font-bold">Mes articles</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn-primary">
            ➕ Nouvel article
        </a>
    </div>

    <!-- Table (Responsive) -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full">
            <thead style="background-color: var(--cream);">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold">Titre</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Statut</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Commentaires</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($posts as $post)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-accent">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-bold" 
                                style="background-color: {{ $post->status === 'published' ? '#d1fae5' : '#f3f4f6' }}; color: {{ $post->status === 'published' ? '#047857' : '#6b7280' }};">
                                {{ $post->status === 'published' ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $post->all_comments_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm flex gap-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn-primary text-xs">
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-primary text-xs bg-red-600 hover:bg-red-700" onclick="return confirm('Êtes-vous sûr ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                            Aucun article. <a href="{{ route('admin.posts.create') }}" class="text-accent font-bold">Créer le premier</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
@endsection

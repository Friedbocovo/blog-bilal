@php
// Highlight mentions in comment body
$highlightedBody = preg_replace(
    '/@([\w]+)/',
    '<span class="mention">@$1</span>',
    e($comment->body)
);
@endphp

<div class="mb-6 p-4 border-l-4" style="border-color: var(--accent);" id="comment-{{ $comment->id }}">
    <!-- Author Info -->
    <div class="flex items-start gap-4 mb-3">
        <img src="{{ $comment->author->getAvatarUrl(40) }}" alt="{{ $comment->author->name }}" class="w-10 h-10 rounded-full">
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <p class="font-semibold text-gray-900">{{ $comment->author->name }}</p>
                @if($comment->author->isAdmin())
                    <span class="px-2 py-1 text-xs font-bold text-white rounded" style="background-color: var(--accent);">Admin</span>
                @endif
            </div>
            <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
        </div>

        <!-- Delete Button -->
        @if(auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $comment->user_id))
            <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Supprimer ce commentaire ?')">
                    Supprimer
                </button>
            </form>
        @endif
    </div>

    <!-- Body -->
    <p class="text-gray-700 mb-3">{!! $highlightedBody !!}</p>

    <!-- Reply Button -->
    @auth
        <button class="text-sm" style="color: var(--accent); font-weight: 500;" onclick="toggleReplyForm('reply-{{ $comment->id }}')">
            Répondre
        </button>

        <!-- Reply Form (Hidden by default) -->
        <div id="reply-{{ $comment->id }}" class="hidden mt-3 ml-8 p-4 bg-gray-50 rounded-lg">
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="body" rows="3" placeholder="Votre réponse..." class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2" style="border-color: #ccc;" required></textarea>
                <div class="flex gap-2 mt-2">
                    <button type="submit" class="btn-primary text-sm">Répondre</button>
                    <button type="button" class="px-4 py-2 text-gray-600 text-sm" onclick="toggleReplyForm('reply-{{ $comment->id }}')">Annuler</button>
                </div>
            </form>
        </div>
    @endauth

    <!-- Replies (Recursive) -->
    @if($comment->replies->count() > 0)
        <div class="mt-4 ml-4 space-y-4">
            @foreach($comment->replies as $reply)
                @include('comments._comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>

<script>
function toggleReplyForm(id) {
    const form = document.getElementById(id);
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        form.querySelector('textarea').focus();
    }
}
</script>

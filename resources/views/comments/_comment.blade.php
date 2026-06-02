@php
$highlightedBody = preg_replace('/@([\w]+)/', '<span class="mention">@$1</span>', e($comment->body));
@endphp

<div class="py-4 {{ isset($isReply) && $isReply ? 'ml-12 border-l-2 border-indigo-100 pl-4' : '' }}" id="comment-{{ $comment->id }}">
    <div class="flex gap-3">
        <img src="{{ $comment->author->getAvatarUrl(36) }}" alt="{{ $comment->author->name }}" class="w-9 h-9 rounded-full flex-shrink-0">
        <div class="flex-1 min-w-0">
            <div class="bg-slate-50 rounded-xl px-4 py-3">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span class="font-semibold text-sm text-slate-900">{{ $comment->author->name }}</span>
                    @if($comment->author->isAdmin())
                        <span class="badge badge-accent text-xs">Admin</span>
                    @endif
                    <span class="text-xs text-slate-400 ml-auto">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed">{!! $highlightedBody !!}</p>
            </div>

            <div class="flex items-center gap-4 mt-1.5 ml-1">
                @auth
                    <button onclick="toggleReplyForm('reply-{{ $comment->id }}')"
                        class="text-xs font-medium text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Répondre
                    </button>
                @endauth

                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $comment->user_id))
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Supprimer ce commentaire ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-slate-300 hover:text-red-500 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>

            @auth
            <div id="reply-{{ $comment->id }}" class="hidden mt-3">
                <form method="POST" action="{{ route('comments.store', $post) }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="flex gap-2">
                        <img src="{{ auth()->user()->getAvatarUrl(28) }}" alt="" class="w-7 h-7 rounded-full flex-shrink-0 mt-1">
                        <div class="flex-1">
                            <textarea name="body" rows="2" placeholder="Votre réponse..."
                                class="input resize-none text-sm mb-2" required></textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="btn btn-primary text-xs px-3 py-1.5">Répondre</button>
                                <button type="button" onclick="toggleReplyForm('reply-{{ $comment->id }}')"
                                    class="btn btn-outline text-xs px-3 py-1.5">Annuler</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endauth

            @if($comment->replies->count() > 0)
                <div class="mt-3 space-y-1">
                    @foreach($comment->replies as $reply)
                        @include('comments._comment', ['comment' => $reply, 'isReply' => true])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleReplyForm(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
    if (!el.classList.contains('hidden')) el.querySelector('textarea').focus();
}
</script>

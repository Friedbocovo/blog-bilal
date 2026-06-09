@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-playfair text-3xl font-bold text-slate-900">Notifications</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $notifications->total() }} notification(s)</p>
        </div>
        @if($notifications->count() > 0)
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="btn btn-outline text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Tout marquer lu
                </button>
            </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-3 mb-8">
            @foreach($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                    $isNewComment = isset($data['type']) && $data['type'] === 'new_comment';
                    $url = route('posts.show', $data['post_slug']) . '#comment-' . $data['comment_id'];
                @endphp

                <a href="{{ $url }}"
                    onclick="markRead('{{ $notification->id }}')"
                    class="flex items-start gap-4 p-4 rounded-2xl border transition-all hover:shadow-md {{ $isUnread ? 'bg-indigo-50 border-indigo-100' : 'bg-white border-slate-100' }}">

                    <!-- Icon -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $isNewComment ? 'bg-green-100' : 'bg-indigo-100' }}">
                        @if($isNewComment)
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900">
                            @if($isNewComment)
                                <span class="text-green-700">{{ $data['from_user'] }}</span>
                                a commenté votre article
                            @else
                                <span class="text-indigo-700">{{ '@'.($data['from_username'] ?? $data['from_user']) }}</span>
                                vous a mentionné
                            @endif
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">
                            📄 {{ $data['post_title'] }}
                        </p>
                        @if(!empty($data['excerpt']))
                            <p class="text-sm text-slate-600 mt-1.5 line-clamp-2 italic">
                                "{{ $data['excerpt'] }}"
                            </p>
                        @endif
                        <p class="text-xs text-slate-400 mt-1.5">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Unread dot -->
                    @if($isUnread)
                        <div class="w-2.5 h-2.5 bg-indigo-500 rounded-full flex-shrink-0 mt-1"></div>
                    @endif
                </a>
            @endforeach
        </div>

        {{ $notifications->links() }}
    @else
        <div class="text-center py-20 bg-white rounded-2xl border border-slate-100">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 mb-1">Aucune notification</h3>
            <p class="text-slate-500 text-sm">Vous serez notifié des nouveaux commentaires ici.</p>
        </div>
    @endif
</div>

<script>
function markRead(id) {
    fetch(`/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
}
</script>
@endsection

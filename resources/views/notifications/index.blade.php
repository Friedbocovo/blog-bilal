@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="font-playfair text-4xl font-bold mb-8">Mes notifications</h1>

    @if($notifications->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($notifications as $notification)
                <a href="{{ route('posts.show', $notification->data['post_slug']) }}#comment-{{ $notification->data['comment_id'] }}" 
                   class="block p-4 rounded-lg transition-colors"
                   style="background-color: {{ is_null($notification->read_at) ? '#fef3c7' : '#f3f4f6' }};">
                    
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">💬</span>
                        
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">
                                <span class="text-accent">{{ '@'.$notification->data['from_username'] }}</span>
                                vous a mentionné
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                dans "{{ $notification->data['post_title'] }}"
                            </p>
                            <p class="text-sm text-gray-600 mt-2">
                                <em>{{ substr($notification->data['excerpt'], 0, 100) }}...</em>
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if(is_null($notification->read_at))
                            <div class="w-3 h-3 rounded-full" style="background-color: var(--accent);"></div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        {{ $notifications->links() }}
    @else
        <div class="text-center py-12 bg-white rounded-lg">
            <p class="text-xl text-gray-600">Aucune notification.</p>
        </div>
    @endif
</div>
@endsection

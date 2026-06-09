<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewComment extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_comment',
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'post_slug' => $this->comment->post->slug,
            'post_title' => $this->comment->post->title,
            'from_user' => $this->comment->author->name,
            'from_username' => $this->comment->author->username ?? '',
            'excerpt' => substr($this->comment->body, 0, 100),
        ];
    }
}

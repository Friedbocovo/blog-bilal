<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Mention;
use App\Models\Post;
use App\Models\User;
use App\Notifications\MentionedInComment;
use App\Notifications\NewComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:2|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        // Notifier l'auteur du post si ce n'est pas lui qui commente
        $postAuthor = $post->author;
        if ($postAuthor && $postAuthor->id !== auth()->id()) {
            $postAuthor->notify(new NewComment($comment));
        }

        // Detect mentions
        preg_match_all('/@([\w]+)/', $validated['body'], $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $username) {
                $mentionedUser = User::where('username', $username)->first();

                if ($mentionedUser && $mentionedUser->id !== auth()->id()) {
                    Mention::create([
                        'comment_id' => $comment->id,
                        'mentioned_user_id' => $mentionedUser->id,
                    ]);

                    $mentionedUser->notify(new MentionedInComment($comment));
                }
            }
        }

        return back()->with('success', 'Commentaire publié avec succès !');
    }

    public function destroy(Comment $comment)
    {
        if (!auth()->user()->isAdmin() && auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé.');
    }
}


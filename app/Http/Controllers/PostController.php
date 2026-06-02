<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->with('author')
            ->withCount('allComments')
            ->paginate(9);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Check if it's a draft and user is not admin
        if ($post->status === 'draft' && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(404);
        }

        $post->load([
            'author',
            'comments.author',
            'comments.replies.author',
            'comments.mentions',
        ]);

        return view('posts.show', compact('post'));
    }
}


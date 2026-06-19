<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $posts = Post::with('author')
            ->withCount(['allComments', 'likes'])
            ->latest()
            ->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string|max:500',
            'content'     => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'media.*'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,webm|max:20480',
            'status'      => 'required|in:draft,published',
        ]);

        // Upload cover image to Cloudinary
        if ($request->hasFile('cover_image')) {
            $result = cloudinary()->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'blog/covers',
            ]);
            $validated['cover_image'] = $result->getSecurePath();
        }

        // Upload media files to Cloudinary
        $validated['media'] = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $isVideo = in_array($file->getClientOriginalExtension(), ['mp4', 'webm']);
                $result = cloudinary()->upload($file->getRealPath(), [
                    'folder'        => 'blog/media',
                    'resource_type' => $isVideo ? 'video' : 'image',
                ]);
                $validated['media'][] = $result->getSecurePath();
            }
        }

        $validated['user_id'] = auth()->id();

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Article créé avec succès !');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'excerpt'     => 'nullable|string|max:500',
            'content'     => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'media.*'     => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,webm|max:20480',
            'status'      => 'required|in:draft,published',
        ]);

        // Upload new cover image to Cloudinary
        if ($request->hasFile('cover_image')) {
            $result = cloudinary()->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'blog/covers',
            ]);
            $validated['cover_image'] = $result->getSecurePath();
        }

        // Append new media files to Cloudinary
        $media = $post->media ?? [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $isVideo = in_array($file->getClientOriginalExtension(), ['mp4', 'webm']);
                $result = cloudinary()->upload($file->getRealPath(), [
                    'folder'        => 'blog/media',
                    'resource_type' => $isVideo ? 'video' : 'image',
                ]);
                $media[] = $result->getSecurePath();
            }
            $validated['media'] = $media;
        }

        if ($validated['status'] === 'published' && is_null($post->published_at)) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Article modifié avec succès !');
    }

    public function destroy(Post $post)
    {
        // Les URLs Cloudinary sont permanentes, pas besoin de les supprimer manuellement
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Article supprimé.');
    }
}

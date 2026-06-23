<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query();

        // Search
        if ($request->filled('search')) {
            $posts->where('title', 'like', '%' . $request->search . '%');
        }

        // Status Filter
        if ($request->status == 'locked') {
            $posts->whereNotNull('locked_at');
        }

        if ($request->status == 'unlocked') {
            $posts->whereNull('locked_at');
        }

        $posts = $posts
            ->oldest()
            ->paginate(4)
            ->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        Post::create($request->all());

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if ($post->isLocked()) {

            return redirect()
                ->route('posts.index')
                ->with('error', 'Post is locked');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->isLocked()) {

            return back()
                ->with('error', 'Locked post cannot be updated');
        }

        $post->update($request->all());

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->isLocked()) {

            return back()
                ->with('error', 'Locked post cannot be deleted');
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    public function lock(Post $post)
    {

        $post->lock();

        $post->histories()->create([

            'action' => 'locked',

            'reason' => 'Post locked for review'

        ]);

        return back()
            ->with(
                'success',
                'Post locked successfully.'
            );
    }

    public function unlock(Post $post)
    {

        $post->unlock();

        $post->histories()->create([

            'action' => 'unlocked',

            'reason' => 'Post approved'

        ]);

        return back()
            ->with(
                'success',
                'Post unlocked successfully.'
            );
    }

    public function show(Post $post)
    {

        return view(
            'posts.show',
            compact('post')
        );
    }

    public function history(Post $post)
    {

        $history = $post->histories()
            ->orderBy('id', 'asc')
            ->get();


        return view(
            'posts.history',
            compact(
                'post',
                'history'
            )
        );
    }
}

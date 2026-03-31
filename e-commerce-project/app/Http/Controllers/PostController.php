<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller implements HasMiddleware
{
    
     public static function middleware(): array
    {
        return [
            new Middleware('permission:view post', only: ['index', 'show']),
            new Middleware('permission:create post', only: ['create', 'store']),
            new Middleware('permission:update post', only: ['edit', 'update']),
            new Middleware('permission:delete post', only: ['destroy']),
        ];
    }

    public function index()
    {
        $posts = Post::all();
        return view('dashboards.posts.postsData', compact('posts'));
    }

    public function create()
    {
        return view('dashboards.posts.addPost');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('posts.index');
    }
 
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return view('dashboards.posts.viewPost', compact('post'));
    }

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        return view('dashboards.posts.updatePost', compact('post'));
    }

    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
        ]);


        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);


        return redirect()->route('posts.index');
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('posts.index');
    }
}

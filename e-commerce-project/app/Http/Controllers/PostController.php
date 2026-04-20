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
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.posts.postsData', compact('posts'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.posts.postsData', compact('posts'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.posts.postsData', compact('posts'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.posts.postsData', compact('posts'));
        }
        // return view('dashboards.posts.postsData', compact('posts'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.posts.addPost');
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.posts.addPost');
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.posts.addPost');
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.posts.addPost');
        }
        // return view('dashboards.posts.addPost');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('posts.index')->with('status', 'Post Add Successfully.');
    }
 
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.posts.viewPost', compact('post'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.posts.viewPost', compact('post'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.posts.viewPost', compact('post'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.posts.viewPost', compact('post'));
        }
        // return view('dashboards.posts.viewPost', compact('post'));
    }

    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.posts.updatePost', compact('post'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.posts.updatePost', compact('post'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.posts.updatePost', compact('post'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.posts.updatePost', compact('post'));
        }
        // return view('dashboards.posts.updatePost', compact('post'));
    }

    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
        ]);


        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);


        return redirect()->route('posts.index')->with('status', 'Post Updated Successfully.');
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('posts.index')->with('status', 'Post Deleted Successfully.');
    }
}

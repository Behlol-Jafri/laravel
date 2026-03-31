<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json([
            'status' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $image->move(public_path() . '/uploads', $imageName);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'data' => $post,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        return response()->json([
            'status' => true,
            'data' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable',
        ]);
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
            'status' => false,
            'message' => 'user not found',
        ]);
        }
            if ($request->image != '') {
                if ($post->image != '' && $post->image != null) {
                    $old_file = public_path() . '/uploads/' . $post->image;
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }

                $image = $request->image;
                $ext = $image->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $image->move(public_path() . '/uploads', $imageName);
            } else {
                $imageName = $post->image;
            }

            $post->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            return response()->json([
                'status' => true,
                'data' => $post,
            ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        $filePath = public_path() . '/uploads/' . $post->image;
        unlink($filePath);

        $post->delete();

        return response()->json([
            'status' => true,
            'data' => $post,
        ]);
    }
}

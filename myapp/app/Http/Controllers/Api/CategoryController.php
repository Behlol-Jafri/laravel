<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category , 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Category::find($id);
        //    $category = Category::with('subCategories')->find($id);

        // if (!$category) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Category not found'
        //     ], 404);
        // }

        // return response()->json([
        //     'success' => true,
        //     'data' => $category
        // ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

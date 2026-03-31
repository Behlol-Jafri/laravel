<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
// use Dotenv\Validator;
use Illuminate\Http\Request;
// use PhpParser\Node\Stmt\TryCatch;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SubCategory::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            // // ✅ Validation
            // $validator = Validator::make($request->all(), [
            //     'category_id' => 'required|exists:categories,id',
            //     'name'        => 'required|string|max:255',
            //     'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            // ]);

            // if ($validator->fails()) {
            //     return response()->json([
            //         'success' => false,
            //         'errors'  => $validator->errors()
            //     ], 422);
            // }


            // ✅ Image upload
            $imageName = null;
            if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('subcategories'), $imageName);
            }

            // ✅ Store data
            $subCategory = SubCategory::create([
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'image'       => $imageName,
            ]);


            return response()->json([
                'success' => true,
                'data'    => $subCategory
            ], 201);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $th->getMessage() // hide in production
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return SubCategory::find($id);
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

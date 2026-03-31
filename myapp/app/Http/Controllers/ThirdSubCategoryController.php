<?php

namespace App\Http\Controllers;

use App\Models\ThirdSubCategory;
use Illuminate\Http\Request;

class ThirdSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ThirdSubCategory::all();
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
                $image->move(public_path('thirdsubcategories'), $imageName);
            }

            // ✅ Store data
            $thirdSubCategory = ThirdSubCategory::create([
                'second_sub_category_id' => $request->second_sub_category_id,
                'name'        => $request->name,
                'image'       => $imageName,
            ]);


            return response()->json([
                'success' => true,
                'data'    => $thirdSubCategory
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
        return ThirdSubCategory::find($id);
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

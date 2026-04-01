<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
     public function index(Category $category)
    {
        $subCategories = $category->subCategory;
        return view('dashboards.subCategory.subCategoryData', compact('subCategories','category'));
    }

    public function create(Category $category)
    {
        return view('dashboards.subCategory.addSubCategory', compact('category'));
    }

    public function store(Request $request,Category $category)
    {
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
        ]);
        $subCategory = SubCategory::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $category->id,
        ]);

        return redirect()->route('category.subCategory.index',$category);
    }

    public function show(Category $category,SubCategory $subCategory)
    {
        return view('dashboards.subCategory.viewSubCategory', compact('subCategory','category'));
    }

    public function edit(Category $category,SubCategory $subCategory)
    {
        return view('dashboards.subCategory.updateSubCategory', compact('subCategory','category'));
    }

    public function update(Request $request,Category $category,SubCategory $subCategory)
    {
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
        ]);


        $subCategory->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $category->id,
        ]);


        return redirect()->route('category.subCategory.index',$category);
    }

    public function destroy(Category $category,SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('category.subCategory.index',$category);
    }
}

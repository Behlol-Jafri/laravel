<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCategoryController extends Controller
{
     public function index(Category $category)
    {
        $subCategories = $category->subCategory;
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.subCategory.subCategoryData', compact('subCategories','category'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.subCategory.subCategoryData', compact('subCategories','category'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.subCategory.subCategoryData', compact('subCategories','category'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.subCategory.subCategoryData', compact('subCategories','category'));
        }
        // return view('dashboards.subCategory.subCategoryData', compact('subCategories','category'));
    }

    public function create(Category $category)
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.subCategory.addSubCategory', compact('category'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.subCategory.addSubCategory', compact('category'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.subCategory.addSubCategory', compact('category'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.subCategory.addSubCategory', compact('category'));
        }
        // return view('dashboards.subCategory.addSubCategory', compact('category'));
    }

    public function store(Request $request,Category $category)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
        ]);
        $subCategory = SubCategory::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $category->id,
        ]);

        return redirect()->route('category.subCategory.index',$category)->with('status', 'Sub Category Add Successfully.');
    }

    public function show(Category $category,SubCategory $subCategory)
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.subCategory.viewSubCategory', compact('subCategory','category'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.subCategory.viewSubCategory', compact('subCategory','category'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.subCategory.viewSubCategory', compact('subCategory','category'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.subCategory.viewSubCategory', compact('subCategory','category'));
        }
        // return view('dashboards.subCategory.viewSubCategory', compact('subCategory','category'));
    }

    public function edit(Category $category,SubCategory $subCategory)
    {
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.subCategory.updateSubCategory', compact('subCategory','category'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.subCategory.updateSubCategory', compact('subCategory','category'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.subCategory.updateSubCategory', compact('subCategory','category'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.subCategory.updateSubCategory', compact('subCategory','category'));
        }
        // return view('dashboards.subCategory.updateSubCategory', compact('subCategory','category'));
    }

    public function update(Request $request,Category $category,SubCategory $subCategory)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
        ]);


        $subCategory->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $category->id,
        ]);


        return redirect()->route('category.subCategory.index',$category)->with('status', 'Sub Category Updated Successfully.');
    }

    public function destroy(Category $category,SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('category.subCategory.index',$category)->with('status', 'Sub Category Deleted Successfully.');
    }
}

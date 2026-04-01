<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(SubCategory $subCategory)
    {
        $products = $subCategory->product;
        $category = Category::findOrFail($subCategory->category_id);
        return view('dashboards.product.productData', compact('products','subCategory','category'));
    }

    public function create(SubCategory $subCategory)
    {
        return view('dashboards.product.addProduct', compact('subCategory'));
    }

    public function store(Request $request,SubCategory $subCategory)
    {
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
            'price' => 'required|numeric',
        ]);
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'subCategory_id' => $subCategory->id,
        ]);

        return redirect()->route('subCategory.product.index',$subCategory);
    }

    public function show(SubCategory $subCategory,Product $product)
    {
        return view('dashboards.product.viewProduct', compact('subCategory','product'));
    }

    public function edit(SubCategory $subCategory,Product $product)
    {
        return view('dashboards.product.updateProduct', compact('subCategory','product'));
    }

    public function update(Request $request,SubCategory $subCategory,Product $product)
    {
        $request->validate([
            'title' => 'required|min:3|max:15',
            'description' => 'required|min:15|max:50',
            'price' => 'required|numeric',
        ]);


        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'subCategory_id' => $subCategory->id,
        ]);


        return redirect()->route('subCategory.product.index',$subCategory);
    }

    public function destroy(SubCategory $subCategory,Product $product)
    {
        $product->delete();
        return redirect()->route('subCategory.product.index',$subCategory);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $categories = Category::all();
        $subCategories = SubCategory::all();
        if (Auth::user()->hasRole('Vender')) {
            $products = Product::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
        } else {
            $products = Product::orderBy('id', 'desc')->paginate(10);
        }
        return view('dashboards.product.productData', compact('products', 'users', 'categories', 'subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        return view('dashboards.product.addProduct', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'category' => 'required',
            'subCategory' => 'required',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,png,jpeg,webp',
        ]);
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category,
            'subCategory_id' => $request->subCategory,
            'user_id' => Auth::user()->id,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('uploads/products'), $imageName);

                $path = 'uploads/products/' . $imageName;


                $product->images()->create([
                    'image' => $path
                ]);
            }
        }

        return redirect()->route('product.index')->with('status', 'Product Add Successfully.');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        Gate::authorize('view', $product);
        return view('dashboards.product.viewProduct', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        return view('dashboards.product.updateProduct', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, string $id,)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title' => 'required|string|min:3|max:15',
            'description' => 'required|string|min:5|max:50',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'category' => 'required',
            'subCategory' => 'required',
            'images.*' => 'image|mimes:jpg,png,jpeg,webp',
        ]);


        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category,
            'subCategory_id' => $request->subCategory,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('uploads/products'), $imageName);

                $path = 'uploads/products/' . $imageName;


                $product->images()->create([
                    'image' => $path
                ]);
            }
        }

        return redirect()->route('product.index')->with('status', 'Product Updated Successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $image) {
            if ($image->image) {
                $filePath = public_path($image->image);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $image->delete();
            }
        }
        $product->delete();
        return redirect()->route('product.index')->with('status', 'Product Deleted Successfully.');
    }

    public function deleteImage(string $id)
    {
        $image = ProductImage::findOrFail($id);

        $filePath = public_path($image->image);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();

        return back();
    }



    public function filter(Request $request)
    {
        $query = Product::query();

        if (Auth::user()->hasRole('Vender')) {
            $query->where('user_id', Auth::user()->id);
        } else {
            $query->when($request->venderId, function ($q) use ($request) {
                $q->where('user_id', $request->venderId);
            });
        }

        $query->when($request->categoryId, function ($q) use ($request) {
            $q->where('category_id', $request->categoryId);
        });

        $query->when($request->subCategoryId, function ($q) use ($request) {
            $q->where('subCategory_id', $request->subCategoryId);
        });

        $products = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'html' => view('dashboards.product.productTable', compact('products'))->render(),
            'pagination' => view('dashboards.product.pagination', compact('products'))->render(),
            'count' => $products->total(),
        ]);
    }

    public function review(Request $request, string $id)
    {

        $product = Product::findOrFail($id);

        $product->update([
            'review_status' => $request->review_status,
            'admin_message' => $request->admin_message,
            'is_read' => false
        ]);

        return back()->with('status', 'Review sent!');
    }

    public function reviewRead(string $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'is_read' => true
        ]);

        return back()->with('status', 'Review as read');
    }
}

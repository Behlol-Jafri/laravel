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
            $products = Product::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(9);
        } else {
            $products = Product::orderBy('id', 'desc')->paginate(9);
        }
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.product.productData', compact('products', 'users', 'categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.product.productData', compact('products', 'users', 'categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.product.productData', compact('products', 'users', 'categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.product.productData', compact('products', 'users', 'categories', 'subCategories'));
        }
        // return view('dashboards.product.productData', compact('products', 'users', 'categories', 'subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.product.addProduct', compact('categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.product.addProduct', compact('categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.product.addProduct', compact('categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.product.addProduct', compact('categories', 'subCategories'));
        }
        // return view('dashboards.product.addProduct', compact('categories', 'subCategories'));
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
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.product.viewProduct', compact('product'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.product.viewProduct', compact('product'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.product.viewProduct', compact('product'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.product.viewProduct', compact('product'));
        }
        // return view('dashboards.product.viewProduct', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        if (Auth::user()->hasRole('Super Admin')) {
            return view('dashboards.super-admin.product.updateProduct', compact('product','categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('Admin')) {
            return view('dashboards.admin.product.updateProduct', compact('product','categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('Vender')) {
            return view('dashboards.vender.product.updateProduct', compact('product','categories', 'subCategories'));
        } elseif (Auth::user()->hasRole('User')) {
            return view('dashboards.user.product.updateProduct', compact('product','categories', 'subCategories'));
        }
        // return view('dashboards.product.updateProduct', compact('product', 'categories', 'subCategories'));
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

        if(Auth::user()->hasRole('User')){
            if (!empty($request->categoryIds)) {
                $query->whereIn('category_id', $request->categoryIds);
            }

            if (!empty($request->subCategoryIds)) {
                $query->whereIn('subCategory_id', $request->subCategoryIds);
            }
            if ($request->priceRanges) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->priceRanges as $range) {
                        if (str_contains($range, '+')) {
                            $min = (int) str_replace('+', '', $range);
                            $q->orWhere('price', '>=', $min);
                        } 
                        else {
                            [$min, $max] = explode('-', $range);
                            $q->orWhereBetween('price', [(int)$min, (int)$max]);
                        }
                    }
                });
            }
        } else{
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
        }

        $products = $query->orderBy('id', 'desc')->paginate(9);

         if (Auth::user()->hasRole('Super Admin')) {
            $viewTable = 'dashboards.super-admin.product.productTable';
            $viewPagination = 'dashboards.super-admin.product.pagination';
        } elseif (Auth::user()->hasRole('Admin')) {
            $viewTable = 'dashboards.admin.product.productTable';
            $viewPagination = 'dashboards.admin.product.pagination';
        } elseif (Auth::user()->hasRole('Vender')) {
            $viewTable = 'dashboards.vender.product.productTable';
            $viewPagination = 'dashboards.vender.product.pagination';
        } elseif (Auth::user()->hasRole('User')) {
            $viewTable = 'dashboards.user.product.productTable';
            $viewPagination = 'dashboards.user.product.pagination';
        }


        return response()->json([
            'html' => view($viewTable, compact('products'))->render(),
            'pagination' => $products->links()->toHtml(),
            'currentCount' => $products->count(),
            'total' => $products->total(),
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

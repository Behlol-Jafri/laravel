<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProductController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user() || Auth::user()->hasRole('User')) {
            $users = User::all();
            $categories = Category::all();
            $subCategories = SubCategory::all();

            if ($request->ajax()) {
                $offset = (int) $request->get('offset', 0);
                $limit = 5;

                $query = Product::orderBy('id', 'desc');

                if ($request->filled('categoryIds')) {
                    $query->whereIn('category_id', $request->categoryIds);
                }
                if ($request->filled('subCategoryIds')) {
                    $query->whereIn('subCategory_id', $request->subCategoryIds);
                }
                if ($request->filled('priceRanges')) {
                    $query->where(function ($q) use ($request) {
                        foreach ($request->priceRanges as $range) {
                            if (str_contains($range, '+')) {
                                $min = (int) str_replace('+', '', $range);
                                $q->orWhere('price', '>=', $min);
                            } else {
                                [$min, $max] = explode('-', $range);
                                $q->orWhereBetween('price', [(int)$min, (int)$max]);
                            }
                        }
                    });
                }

                $products = $query->offset($offset)->limit($limit)->get();
                $showEmptyCatalogMessage = $offset === 0;

                return response()->json([
                    'html'    => view('dashboards.user.product.productTable', compact('products', 'showEmptyCatalogMessage'))->render(),
                    'hasMore' => $products->count() == $limit,
                ]);
            }

            $products = Product::orderBy('id', 'desc')->offset(0)->limit(5)->get();
            $showEmptyCatalogMessage = true;

            return view('dashboards.user.product.productData', compact('products', 'users', 'categories', 'subCategories', 'showEmptyCatalogMessage'));
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function filter(Request $request)
    {
        $limit = 5;
        $offset = (int) $request->get('offset', 0);

        $query = Product::query();

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
                    } else {
                        [$min, $max] = explode('-', $range);
                        $q->orWhereBetween('price', [(int)$min, (int)$max]);
                    }
                }
            });
        }

        $products = $query->orderBy('id', 'desc')->offset($offset)->limit($limit)->get();
        $showEmptyCatalogMessage = $offset === 0;

        return response()->json([
            'html'    => view('dashboards.user.product.productTable', compact('products', 'showEmptyCatalogMessage'))->render(),
            'hasMore' => $products->count() == $limit,
        ]);
    }

    public function cartDetails()
    {
        return view('dashboards.user.product.productOrderDetails');
    }

    public function orderDetails()
    {
        return view('dashboards.user.product.orderDetails');
    }
}

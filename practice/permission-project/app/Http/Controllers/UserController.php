<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();

        // Vendor IDs this user has access to
        $accessibleVendorIds = $user->receivedAccesses()->where('is_active', true)->pluck('granter_id');

        $stats = [
            'accessible_vendors'  => $accessibleVendorIds->count(),
            'available_products'  => Product::whereIn('vendor_id', $accessibleVendorIds)->where('is_active', true)->count(),
            'my_orders'           => $user->orders()->count(),
            'pending_orders'      => $user->orders()->where('status', 'pending')->count(),
        ];

        $recentOrders = $user->orders()->with('product')->latest()->take(5)->get();
        $featuredProducts = Product::whereIn('vendor_id', $accessibleVendorIds)
            ->where('is_active', true)->latest()->take(6)->get();

        return view('user.dashboard', compact('stats', 'recentOrders', 'featuredProducts'));
    }

    public function products()
    {
        $user = Auth::user();
        $accessibleVendorIds = $user->receivedAccesses()->where('is_active', true)->pluck('granter_id');
        $products = Product::whereIn('vendor_id', $accessibleVendorIds)
            ->where('is_active', true)
            ->with('vendor')
            ->latest()->paginate(12);
        return view('user.products', compact('products'));
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('product.vendor')->latest()->paginate(15);
        return view('user.orders', compact('orders'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $user    = Auth::user();

        // Check if user has access to this vendor's products
        $hasAccess = $user->receivedAccesses()
            ->where('granter_id', $product->vendor_id)
            ->where('is_active', true)
            ->exists();

        if (!$hasAccess) abort(403, 'You do not have access to order this product.');

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        Order::create([
            'user_id'     => $user->id,
            'product_id'  => $product->id,
            'quantity'    => $request->quantity,
            'total_price' => $product->price * $request->quantity,
            'status'      => 'pending',
            'notes'       => $request->notes,
        ]);

        $product->decrement('stock', $request->quantity);

        return back()->with('success', 'Order placed successfully!');
    }

    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }
}

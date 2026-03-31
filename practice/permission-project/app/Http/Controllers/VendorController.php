<?php

namespace App\Http\Controllers;

use App\Models\AccessGrant;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    public function dashboard()
    {
        $vendor = Auth::user();

        // Users this vendor has access to
        $grantedUserIds = $vendor->grantedAccesses->where('is_active', true)->pluck('grantee_id');

        $stats = [
            'my_products' => $vendor->products->count(),
            'my_orders'   => Order::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))->count(),
            'my_users'    => $grantedUserIds->count(),
            'revenue'     => Order::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
                                  ->where('status', 'completed')->sum('total_price'),
        ];

        $recentOrders = Order::with(['user', 'product'])
            ->whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))
            ->latest()->take(5)->get();

        return view('vendor.dashboard', compact('stats', 'recentOrders'));
    }

    // ─── Products ─────────────────────────────────────────────────────────────────
    public function products()
    {
        $products = Product::where('vendor_id', Auth::id())->latest()->paginate(15);
        return view('vendor.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('vendor.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
        ]);

        Product::create([
            'vendor_id'   => Auth::id(),
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'category'    => $request->category,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('vendor.products')->with('success', 'Product created.');
    }

    public function editProduct(Product $product)
    {
        if ($product->vendor_id !== Auth::id()) abort(403);
        return view('vendor.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        if ($product->vendor_id !== Auth::id()) abort(403);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
        ]);

        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'category'    => $request->category,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('vendor.products')->with('success', 'Product updated.');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->vendor_id !== Auth::id()) abort(403);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    // ─── Orders ───────────────────────────────────────────────────────────────────
    public function orders()
    {
        $orders = Order::with(['user', 'product'])
            ->whereHas('product', fn($q) => $q->where('vendor_id', Auth::id()))
            ->latest()->paginate(15);
        return view('vendor.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        if (!$order->product || $order->product->vendor_id !== Auth::id()) abort(403);
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }

    // ─── Users granted to this vendor ────────────────────────────────────────────
    public function users()
    {
        $vendor = Auth::user();
        $grantedUserIds = $vendor->grantedAccesses->where('is_active', true)->pluck('grantee_id');
        $users = User::where('role', 'user')->whereIn('id', $grantedUserIds)->paginate(15);
        return view('vendor.users.index', compact('users'));
    }

    public function viewUser(User $user)
    {
        $vendor = Auth::user();
        if (!$vendor->grantedAccesses->where('grantee_id', $user->id)->where('is_active', true)->exists()) {
            abort(403, 'You do not have access to this user.');
        }
        $orders = $user->orders()->with('product')->latest()->paginate(10);
        return view('vendor.users.show', compact('user', 'orders'));
    }

    // ─── Access Management ────────────────────────────────────────────────────────
    public function accessManagement()
    {
        $vendor = Auth::user();
        $allUsers = User::where('role', 'user')->get();
        $currentGrants = AccessGrant::with(['granter', 'grantee'])
            ->where('granter_id', $vendor->id)
            ->get();

        return view('vendor.access.index', compact('allUsers', 'currentGrants'));
    }

    public function grantAccess(Request $request)
    {
        $request->validate([
            'grantee_ids'   => 'required|array',
            'grantee_ids.*' => 'exists:users,id',
            'access_level'  => 'required|in:read,write,full',
            'expires_at'    => 'nullable|date|after:today',
        ]);

        $vendor = Auth::user();
        $granted = 0;

        foreach ($request->grantee_ids as $granteeId) {
            $grantee = User::findOrFail($granteeId);
            if (!$grantee->isUser()) continue;

            AccessGrant::updateOrCreate(
                ['granter_id' => $vendor->id, 'grantee_id' => $granteeId],
                [
                    'access_level' => $request->access_level,
                    'is_active'    => true,
                    'expires_at'   => $request->expires_at,
                ]
            );
            $granted++;
        }

        return back()->with('success', "Access granted to {$granted} user(s).");
    }

    public function revokeAccess(AccessGrant $grant)
    {
        if ($grant->granter_id !== Auth::id()) abort(403);
        $grant->delete();
        return back()->with('success', 'Access revoked.');
    }

    public function revokeMultipleAccess(Request $request)
    {
        $request->validate(['grant_ids' => 'required|array']);
        AccessGrant::whereIn('id', $request->grant_ids)->where('granter_id', Auth::id())->delete();
        return back()->with('success', 'Selected access revoked.');
    }
}

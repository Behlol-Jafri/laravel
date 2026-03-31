<?php

namespace App\Http\Controllers;

use App\Models\AccessGrant;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        $admin = Auth::user();

        // Get vendor IDs this admin has been granted access to
        $grantedVendorIds = $admin->grantedAccesses
            ->where('is_active', true)
            ->pluck('grantee_id');

        $stats = [
            'my_vendors'  => $grantedVendorIds->count(),
            'my_users'    => User::where('role', 'user')->count(),
            'products'    => Product::whereIn('vendor_id', $grantedVendorIds)->count(),
            'orders'      => Order::whereHas('product', fn($q) => $q->whereIn('vendor_id', $grantedVendorIds))->count(),
        ];

        $vendors       = User::whereIn('id', $grantedVendorIds)->get();
        $recentOrders  = Order::with(['user', 'product'])
            ->whereHas('product', fn($q) => $q->whereIn('vendor_id', $grantedVendorIds))
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'vendors', 'recentOrders'));
    }

    // ─── View Vendors granted by Super Admin ─────────────────────────────────────
    public function vendors()
    {
        $admin = Auth::user();
        $grantedVendorIds = $admin->grantedAccesses->where('is_active', true)->pluck('grantee_id');
        $vendors = User::where('role', 'vendor')->whereIn('id', $grantedVendorIds)->paginate(15);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function viewVendor(User $vendor)
    {
        $admin = Auth::user();
        // Check admin has access to this vendor
        if (!$admin->grantedAccesses->where('grantee_id', $vendor->id)->where('is_active', true)->exists()) {
            abort(403, 'You do not have access to this vendor.');
        }
        $products = $vendor->products()->paginate(10);
        $orders   = Order::whereHas('product', fn($q) => $q->where('vendor_id', $vendor->id))->with(['user', 'product'])->latest()->take(10)->get();
        return view('admin.vendors.show', compact('vendor', 'products', 'orders'));
    }

    // ─── Manage Users ─────────────────────────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::where('role', 'user');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => 'user',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function destroyUser(User $user)
    {
        if (!$user->isUser()) abort(403);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    // ─── Access Management ─────────────────────────────────────────────────────────
    public function accessManagement()
    {
        $admin = Auth::user();

        // Vendors this admin can see (granted by super admin)
        $accessibleVendorIds = $admin->grantedAccesses->where('is_active', true)->pluck('grantee_id');
        $vendors = User::where('role', 'vendor')->whereIn('id', $accessibleVendorIds)->get();

        // Users this admin can grant access to
        $allUsers = User::where('role', 'user')->get();

        // Current grants from this admin to vendors/users
        $currentGrants = AccessGrant::with(['granter', 'grantee'])
            ->where('granter_id', $admin->id)
            ->get();

        return view('admin.access.index', compact('vendors', 'allUsers', 'currentGrants'));
    }

    public function grantAccess(Request $request)
    {
        $request->validate([
            'grantee_ids'   => 'required|array',
            'grantee_ids.*' => 'exists:users,id',
            'access_level'  => 'required|in:read,write,full',
            'expires_at'    => 'nullable|date|after:today',
        ]);

        $admin = Auth::user();
        $granted = 0;

        foreach ($request->grantee_ids as $granteeId) {
            $grantee = User::findOrFail($granteeId);
            // Admin can grant access to vendors and users
            if (!in_array($grantee->role, ['vendor', 'user'])) continue;

            AccessGrant::updateOrCreate(
                ['granter_id' => $admin->id, 'grantee_id' => $granteeId],
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

    public function products()
    {
        $admin = Auth::user();
        $grantedVendorIds = $admin->grantedAccesses->where('is_active', true)->pluck('grantee_id');
        $products = Product::whereIn('vendor_id', $grantedVendorIds)->with('vendor')->latest()->paginate(15);
        return view('admin.products', compact('products'));
    }

    public function orders()
    {
        $admin = Auth::user();
        $grantedVendorIds = $admin->grantedAccesses->where('is_active', true)->pluck('grantee_id');
        $orders = Order::with(['user', 'product.vendor'])
            ->whereHas('product', fn($q) => $q->whereIn('vendor_id', $grantedVendorIds))
            ->latest()->paginate(15);
        return view('admin.orders', compact('orders'));
    }
}

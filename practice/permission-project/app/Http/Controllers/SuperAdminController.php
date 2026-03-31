<?php

namespace App\Http\Controllers;

use App\Models\AccessGrant;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{

    // ─── Dashboard ───────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_users'    => User::count(),
            'admins'         => User::where('role', 'admin')->count(),
            'vendors'        => User::where('role', 'vendor')->count(),
            'users'          => User::where('role', 'user')->count(),
            'total_products' => Product::count(),
            'total_orders'   => Order::count(),
            'total_grants'   => AccessGrant::where('is_active', true)->count(),
            'active_users'   => User::where('is_active', true)->count(),
        ];

        $recentUsers   = User::latest()->take(5)->get();
        $recentOrders  = Order::with(['user', 'product'])->latest()->take(5)->get();
        $recentGrants  = AccessGrant::with(['granter', 'grantee'])->latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers', 'recentOrders', 'recentGrants'));
    }

    // ─── User Management ─────────────────────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->role) $query->where('role', $request->role);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        $users = $query->latest()->paginate(15);
        return view('superadmin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('superadmin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|in:super_admin,admin,vendor,user',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:super_admin,admin,vendor,user',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only('name', 'email', 'role', 'phone');
        $data['is_active'] = $request->boolean('is_active');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('superadmin.users')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the only super admin.');
        }
        $user->delete();
        return redirect()->route('superadmin.users')->with('success', 'User deleted.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    // ─── Access Management ────────────────────────────────────────────────────────
    public function accessManagement()
    {
        $admins  = User::where('role', 'admin')->get();
        $vendors = User::where('role', 'vendor')->get();
        $users   = User::where('role', 'user')->get();

        // Current grants by super admin
        $currentGrants = AccessGrant::with(['granter', 'grantee'])
            ->where('granter_id', Auth::id())
            ->get();

        return view('superadmin.access.index', compact('admins', 'vendors', 'users', 'currentGrants'));
    }

    public function grantAccess(Request $request)
    {
        $request->validate([
            'grantee_ids'  => 'required|array',
            'grantee_ids.*'=> 'exists:users,id',
            'access_level' => 'required|in:read,write,full',
            'permissions'  => 'nullable|array',
            'expires_at'   => 'nullable|date|after:today',
        ]);

        $granted = 0;
        foreach ($request->grantee_ids as $granteeId) {
            $grantee = User::findOrFail($granteeId);
            // Super admin can only grant to admin, admin to vendor, vendor to user
            AccessGrant::updateOrCreate(
                ['granter_id' => Auth::id(), 'grantee_id' => $granteeId],
                [
                    'access_level' => $request->access_level,
                    'permissions'  => $request->permissions,
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
        // Only the granter or super admin can revoke
        if ($grant->granter_id !== Auth::id() && !Auth::user()->isSuperAdmin) {
            abort(403);
        }
        $grant->delete();
        return back()->with('success', 'Access revoked successfully.');
    }

    public function revokeMultipleAccess(Request $request)
    {
        $request->validate(['grant_ids' => 'required|array']);
        AccessGrant::whereIn('id', $request->grant_ids)
            ->where('granter_id', Auth::id())
            ->delete();
        return back()->with('success', 'Selected access grants revoked.');
    }

    // ─── Products & Orders ────────────────────────────────────────────────────────
    public function products()
    {
        $products = Product::with('vendor')->latest()->paginate(15);
        return view('superadmin.products', compact('products'));
    }

    public function orders()
    {
        $orders = Order::with(['user', 'product.vendor'])->latest()->paginate(15);
        return view('superadmin.orders', compact('orders'));
    }

    // ─── All Access Grants (global view) ─────────────────────────────────────────
    public function allGrants()
    {
        $grants = AccessGrant::with(['granter', 'grantee'])->latest()->paginate(20);
        return view('superadmin.access.all', compact('grants'));
    }
}

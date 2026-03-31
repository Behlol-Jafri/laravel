<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $auth): bool
    {
        return true; // Everyone can list (filtered by role in controller)
    }

    public function view(User $auth, Product $product): bool
    {
        if ($auth->isSuperAdmin() || $auth->isAdmin()) return true;
        if ($auth->isVendor()) return $auth->id === $product->vendor_id;

        // User can view products from vendors they have access to
        if ($auth->isUser()) {
            return $auth->receivedAccesses()
                ->where('granter_id', $product->vendor_id)
                ->where('is_active', true)
                ->exists();
        }
        return false;
    }

    public function create(User $auth): bool
    {
        return $auth->isVendor() || $auth->isSuperAdmin() || $auth->isAdmin();
    }

    public function update(User $auth, Product $product): bool
    {
        if ($auth->isSuperAdmin()) return true;
        return $auth->id === $product->vendor_id;
    }

    public function delete(User $auth, Product $product): bool
    {
        if ($auth->isSuperAdmin()) return true;
        return $auth->id === $product->vendor_id;
    }
}

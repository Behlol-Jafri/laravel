<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $auth): bool
    {
        return in_array($auth->role, ['super_admin', 'admin', 'vendor']);
    }

    public function view(User $auth, User $target): bool
    {
        if ($auth->isSuperAdmin()) return true;

        // Admin can view vendors/users that have been granted to them
        if ($auth->isAdmin() && in_array($target->role, ['vendor', 'user'])) {
            // Check if super_admin granted this admin access to this vendor/user
            return $auth->hasAccessFrom(
                User::where('role', 'super_admin')->first()?->id ?? 0
            ) || $this->hasGrantedAccess($auth, $target);
        }

        // Vendor can view users granted to them
        if ($auth->isVendor() && $target->isUser()) {
            return $this->hasGrantedAccess($auth, $target);
        }

        return $auth->id === $target->id;
    }

    public function create(User $auth): bool
    {
        return in_array($auth->role, ['super_admin', 'admin']);
    }

    public function update(User $auth, User $target): bool
    {
        if ($auth->isSuperAdmin()) return true;
        if ($auth->id === $target->id) return true;
        if ($auth->isAdmin() && in_array($target->role, ['vendor', 'user'])) return true;
        return false;
    }

    public function delete(User $auth, User $target): bool
    {
        if ($auth->isSuperAdmin()) return true;
        if ($auth->isAdmin() && in_array($target->role, ['vendor', 'user'])) return true;
        return false;
    }

    public function manageAccess(User $auth): bool
    {
        return in_array($auth->role, ['super_admin', 'admin', 'vendor']);
    }

    private function hasGrantedAccess(User $granter, User $grantee): bool
    {
        return $granter->grantedAccesses()
            ->where('grantee_id', $grantee->id)
            ->where('is_active', true)
            ->exists();
    }
}

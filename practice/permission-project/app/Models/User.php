<?php

namespace App\Models;

use App\Models\AccessGrant;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'avatar', 'password', 'role', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // ─── Role Helpers ───────────────────────────────────────────────────────────
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isVendor(): bool     { return $this->role === 'vendor'; }
    public function isUser(): bool       { return $this->role === 'user'; }

    public function hasRole(string $role): bool { return $this->role === $role; }

    // ─── Relationships ───────────────────────────────────────────────────────────
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Access grants this user has GIVEN to others
    public function grantedAccesses()
    {
        return $this->hasMany(AccessGrant::class, 'granter_id');
    }

    // Access grants this user has RECEIVED from others
    public function receivedAccesses()
    {
        return $this->hasMany(AccessGrant::class, 'grantee_id');
    }

    // ─── Access Grant Helpers ────────────────────────────────────────────────────

    /**
     * Check if this user has been granted access by a specific granter.
     */
    public function hasAccessFrom(int $granterId): bool
    {
        return $this->receivedAccesses()
            ->where('granter_id', $granterId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get all users this user can view (based on granted access).
     * Super admin sees everyone. Others see based on grants.
     */
    public function getViewableUsers()
    {
        if ($this->isSuperAdmin()) {
            return User::where('id', '!=', $this->id)->get();
        }

        // Get all grantee IDs from this user's grants
        $granteeIds = $this->grantedAccesses()->where('is_active', true)->pluck('grantee_id');

        return User::whereIn('id', $granteeIds)->get();
    }

    /**
     * Role hierarchy: higher number = more powerful
     */
    public static function roleHierarchy(): array
    {
        return [
            'user'        => 1,
            'vendor'      => 2,
            'admin'       => 3,
            'super_admin' => 4,
        ];
    }

    public function getRoleLevel(): int
    {
        return static::roleHierarchy()[$this->role] ?? 0;
    }

    /**
     * Check if this user is higher role than another user
     */
    public function isHigherThan(User $other): bool
    {
        return $this->getRoleLevel() > $other->getRoleLevel();
    }

    /**
     * Get next lower role for granting purposes
     */
    public function getGrantableRole(): ?string
    {
        $map = [
            'super_admin' => 'admin',
            'admin'       => 'vendor',
            'vendor'      => 'user',
        ];
        return $map[$this->role] ?? null;
    }

    public function getRoleBadgeClass(): string
    {
        return match($this->role) {
            'super_admin' => 'badge-danger',
            'admin'       => 'badge-warning',
            'vendor'      => 'badge-info',
            'user'        => 'badge-success',
            default       => 'badge-secondary',
        };
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'vendor'      => 'Vendor',
            'user'        => 'User',
            default       => 'Unknown',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessGrant extends Model
{
    use HasFactory;

    protected $fillable = [
        'granter_id', 'grantee_id', 'access_level', 'permissions', 'is_active', 'expires_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active'   => 'boolean',
        'expires_at'  => 'datetime',
    ];

    public function granter()
    {
        return $this->belongsTo(User::class, 'granter_id');
    }

    public function grantee()
    {
        return $this->belongsTo(User::class, 'grantee_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->access_level === 'full') return true;
        return in_array($permission, $this->permissions ?? []);
    }
}

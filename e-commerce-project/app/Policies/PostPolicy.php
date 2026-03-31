<?php

namespace App\Policies;

use App\Models\AccessControl;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        if ($user->role === 'Super Admin') {
            return true;
        }
        if ($post->user_id === $user->id) {
            return true;
        }
        if ($user->role === 'Admin' || $user->role === 'Vender' || $user->role === 'User') {
            return AccessControl::where('granted_to', $user->id)
                ->where('target_user', $post->user_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ($user->role === 'Super Admin') {
            return true;
        }
        if ($post->user_id === $user->id) {
            return true;
        }
        if ($user->role === 'Admin' || $user->role === 'Vender' || $user->role === 'User') {
            return AccessControl::where('granted_to', $user->id)
                ->where('target_user', $post->user_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ($user->role === 'Super Admin') {
            return true;
        }
        if ($post->user_id === $user->id) {
            return true;
        }
        if ($user->role === 'Admin' || $user->role === 'Vender' || $user->role === 'User') {
            return AccessControl::where('granted_to', $user->id)
                ->where('target_user', $post->user_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}

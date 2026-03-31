<?php

namespace App\Providers;

use App\Models\UserPermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('read-user-data', function ($authUser, $targetUser) {
            if ($authUser->id === $targetUser->id) return true;
            if ($authUser->isAdmin()) return true;

            return UserPermission::where('user_id', $authUser->id)
                ->where('target_user_id', $targetUser->id)
                ->where('can_read', true)
                ->exists();
        });
    }
}

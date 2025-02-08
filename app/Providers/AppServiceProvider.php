<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
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
        Gate::define('isAdmin',function(User $user){

            return $user->role == 'Admin';
        });

        Gate::define('user_id', function (User $user, Category $category) {
            // Yahan `User` aur `Category` do arguments hain
            return $user->id === $category->user_id;
        });
    }
}

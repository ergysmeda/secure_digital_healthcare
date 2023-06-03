<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Use a view composer to dynamically share the menuData with views
           \View::composer('*', function ($view) {
            $user = Auth::user();
            $role = '';

            if ($user) {
                $role = '_' . $user->role->role_name;
            }

            // Get the menu data based on the user's role
            $verticalMenuJson = file_get_contents(base_path("resources/data/menu-data/verticalMenu{$role}.json"));
            $verticalMenuData = json_decode($verticalMenuJson);
            $horizontalMenuData = null;
            // Share the menuData with the view
            $view->with('menuData', [$verticalMenuData, $horizontalMenuData]);
        });
    }
}

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
        // get all data from menu.json file
        $verticalAdminMenuJson = file_get_contents(base_path('resources/json/admin/verticalMenu.json'));
        $verticalAdminMenuData = json_decode($verticalAdminMenuJson);
        $horizontalAdminMenuJson = file_get_contents(base_path('resources/json/admin/horizontalMenu.json'));
        $horizontalAdminMenuData = json_decode($horizontalAdminMenuJson);

        $verticalUserMenuJson = file_get_contents(base_path('resources/json/user/verticalMenu.json'));
        $verticalUserMenuData = json_decode($verticalUserMenuJson);
        $horizontalUserMenuJson = file_get_contents(base_path('resources/json/user/horizontalMenu.json'));
        $horizontalUserMenuData = json_decode($horizontalUserMenuJson);



        // Share all menuData to all the views
        \View::share('menuData',['admin' => [$verticalAdminMenuData, $horizontalAdminMenuData],
                                 'user' => [$verticalUserMenuData, $horizontalUserMenuData]
                    ]);
    }
}

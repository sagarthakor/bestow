<?php

namespace App\Providers;

use App\category;
use App\subcategory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('categories', Category::with('subcategories')->get());
        });

        $this->app->bind('accountType', function () {

            if (\Session::has('user')) {
                return 'USER';
            } else {
                return 'GUEST';
            }
        });
    }
}

<?php

namespace Modules\Categories\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CategoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::namespace('Modules\Categories\Http\Controllers')
            ->middleware(['web'])
            ->group(__DIR__. '/../Routes/web.php');

            $this->loadViewsFrom(__DIR__.'/../Views', 'Category');

            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            $this->publishes([
                __DIR__.'/../Views' => resource_path('views/vendor/Category'),
            ], 'views');

            
            $this->publishes([
                __DIR__.'/../Config/categories.php' => config_path('categories.php'),
            ], 'config');
            
    }
    public function register()
    {        
        $this->mergeConfigFrom(
            __DIR__.'/../Config/categories.php',
            'categories'
        );
        
    }
}
<?php

namespace SevenSparky\LaravelMobileAuth\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use SevenSparky\LaravelMobileAuth\LaravelMobileAuth;

class LaravelMobileAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('LaravelMobileAuth', function (){
            return new LaravelMobileAuth();
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->_loadRoutes();

        $this->_loadViews();

        $this->_loadMigrations();

        $this->_loadAssets();
    }

    private function _loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/route.php');
    }

    private function _loadViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'LaravelMobileAuth');

        $this->publishes([
            __DIR__. '/../resources/views' => resource_path('views/vendor/LaravelMobileAuth')
        ], 'laravel-mobile-auth-views');
    }

    private function _loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__. '/../database/migrations' => database_path('migrations')
        ], 'laravel-mobile-auth-migrations');
    }

    private function _loadAssets(): void
    {
        $this->publishes([
            __DIR__.'/../resources/views/mobile-auth-assets' => public_path('vendor/mobile-auth')
        ], 'laravel-mobile-auth-assets');
    }

}

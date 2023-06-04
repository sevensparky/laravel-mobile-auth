<?php

namespace SevenSparky\LaravelMobileAuth\ServiceProvider;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use SevenSparky\LaravelMobileAuth\LaravelMobileAuth;

class LaravelMobileAuthRouteServiceProvider extends RouteServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
//            Route::middleware('api')
//                ->prefix('api')
//                ->group(base_path('packages/SevenSparky/laravel-mobile-auth/src/routes/api.php'));

            Route::middleware('web')
                ->group(base_path('packages/SevenSparky/laravel-mobile-auth/src/routes/route.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

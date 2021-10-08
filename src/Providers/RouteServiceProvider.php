<?php

namespace Yjtec\PanoEdit\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'Yjtec\PanoEdit\Controllers';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function map()
    {
        Route::prefix(config('pano_edit.route_prefix'))
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(__DIR__.'/../routes/api.php');
    }
}

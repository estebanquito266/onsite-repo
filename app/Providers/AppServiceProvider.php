<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Observers\HistorialEstadoOnsiteObserver;
use App\Models\Onsite\HistorialEstadoOnsite;
use Illuminate\Support\Facades\Schema;

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
      Passport::routes();
      HistorialEstadoOnsite::observe(HistorialEstadoOnsiteObserver::class);
      Schema::defaultStringLength(150);
    }
}

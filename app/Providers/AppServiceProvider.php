<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Observers\HistorialEstadoOnsiteObserver;
use App\Models\Onsite\HistorialEstadoOnsite;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
      //USAR SOLO LOCAL PARA PRUEBAS
      /*DB::listen(function ($query) {
            Log::info($query->sql);    
            Log::info($query->time);  
        });*/

      Passport::routes();
      HistorialEstadoOnsite::observe(HistorialEstadoOnsiteObserver::class);
      Schema::defaultStringLength(150);
    }
}

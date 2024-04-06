<?php

namespace App\Providers;
//use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // pagination kullanabilmek için bunu yazdım ve aşağırdaki komutu  " Paginator::useBootstrap(); "
use App\Models\Config;
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
        Paginator::useBootstrap(); //
        view()->share('config',Config::find(1));

//        Route::resourceVerbs([
//            'create'=>'olustur',
//            'edit'=>'guncelle',
//        ]);
    }
}

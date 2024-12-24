<?php

namespace App\Providers;

use App\Models\Pembimbing;
use App\Models\Peserta;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\PembimbingObserver;
use App\Observers\PesertaObserver;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Daftarkan observer
        Peserta::observe(PesertaObserver::class);
        Pembimbing::observe(PembimbingObserver::class);
    }
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

    
}

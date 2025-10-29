<?php

namespace App\Providers;

use App\Models\{WilayahTugas, Dsrt, Responden, UserCustom};
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        View::share([
            'totalWilayahTugas' => WilayahTugas::count(),
            'totalDsrt' => Dsrt::count(),
            'totalResponden' => Responden::count(),
            'totalPengguna' => UserCustom::count(),
        ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Blade directive untuk menandai route aktif
        Blade::directive('activeIfRoute', function ($expression) {
            return "<?php echo request()->routeIs($expression) ? 'active' : ''; ?>";
        });
    }
}

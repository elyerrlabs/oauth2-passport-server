<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\ModuleVite;
use Illuminate\Support\Facades\Blade;

class DirectiveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //Register vite for modules module_vite
        $this->app->singleton('module_vite', function ($app) {
            return new ModuleVite();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add blade directive to load module resources @module_vite(['assets'])
        Blade::directive('module_vite', function ($expression) {
            return "<?php echo app('module_vite')($expression); ?>";
        });
    }
}

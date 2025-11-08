<?php

namespace App\Providers;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Livewire\LivewireManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $loader = AliasLoader::getInstance(); //\Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        //layout app
        // Registrar EventServiceProvider
        $this->app->register(\App\Providers\EventServiceProvider::class);
        $this->app->register(\App\Providers\HelperServiceProvider::class);
        // $this->app->register(\App\Providers\UserActivityServiceProvider::class);

        App::setLocale('pt_BR');

        Blade::directive('formatValue', function ($expression) {
            return "<?php echo \App\Models\AuditLog::formatValueStatic($expression); ?>";
});




}
}
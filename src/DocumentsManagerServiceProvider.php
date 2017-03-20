<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Support\ServiceProvider;

class DocumentsManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'documents-migration');

        $this->publishes([
            __DIR__.'/../resources/assets/js/components/core' => base_path('resources/assets/js/components/core'),
        ], 'documents-component');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

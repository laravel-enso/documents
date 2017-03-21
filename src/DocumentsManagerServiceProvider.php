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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'documentsmanager');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'documents-migration');

        $this->publishes([
            __DIR__.'/../resources/assets/js/views' => resource_path('views/vendor/laravel-enso/documentsmanager'),
        ], 'documents-partial');

        $this->publishes([
            __DIR__.'/../resources/assets/js/components' => resource_path('assets/js/components/laravel-enso'),
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

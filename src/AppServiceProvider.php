<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\DocumentsManager\app\Commands\RemovesDeprecatedPermissions;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load();

        $this->publish();

        $this->commands([
            RemovesDeprecatedPermissions::class,
        ]);
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/config/documents.php', 'enso.documents');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'documents-config');

        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/resources/js' => resource_path('js'),
        ], 'documents-assets');

        $this->publishes([
            __DIR__.'/resources/js' => resource_path('js'),
        ], 'enso-assets');
    }

    public function register()
    {
        //
    }
}

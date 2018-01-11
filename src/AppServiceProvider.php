<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesAll();
        $this->loadDependencies();
    }

    private function publishesAll()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'documents-config');

        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');
    }

    private function loadDependencies()
    {
        $this->mergeConfigFrom(__DIR__.'/config/documents.php', 'enso.documents');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    public function register()
    {
        //
    }
}

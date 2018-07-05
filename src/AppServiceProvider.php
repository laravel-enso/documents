<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Observers\DocumentObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesAll();
        $this->loadDependencies();

        Document::observe(DocumentObserver::class);
    }

    private function publishesAll()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'documents-config');

        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], 'enso-config');

        $this->publishes([
            __DIR__.'/resources/assets/js' => resource_path('assets/js'),
        ], 'documents-assets');

        $this->publishes([
            __DIR__.'/resources/assets/js' => resource_path('assets/js'),
        ], 'enso-assets');
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

<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Support\ServiceProvider;

class DocumentsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesAll();
        $this->loadDependencies();
    }

    private function publishesAll()
    {
        $this->publishes([
            __DIR__ . '/config/documents.php' => config_path('documents.php'),
        ], 'documents-config');

        $this->publishes([
            __DIR__ . '/resources/assets/js/components' => resource_path('assets/js/vendor/laravel-enso/components'),
        ], 'documents-component');

        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'enso-config');

        $this->publishes([
            __DIR__ . '/resources/assets/js/components' => resource_path('assets/js/vendor/laravel-enso/components'),
        ], 'enso-update');
    }

    private function loadDependencies()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/documents.php', 'documents');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
    }
}

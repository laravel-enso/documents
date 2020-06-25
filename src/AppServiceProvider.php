<?php

namespace LaravelEnso\Documents;

use Illuminate\Support\ServiceProvider;
use LaravelEnso\Documents\Models\Document;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->publish();

        Document::morphMap();
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/documents.php', 'enso.documents');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/../config' => config_path('enso'),
        ], ['documents-config', 'enso-config']);
    }
}

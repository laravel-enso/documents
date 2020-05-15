<?php

namespace LaravelEnso\Documents;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Documents\App\Models\Document;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->mapMorphs()
            ->publish();
    }

    private function mapMorphs()
    {
        Relation::morphMap([
            Document::morphMapKey() => Document::class,
        ]);

        return $this;
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/config/documents.php', 'enso.documents');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/config' => config_path('enso'),
        ], ['documents-config', 'enso-config']);
    }
}

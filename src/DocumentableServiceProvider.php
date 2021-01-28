<?php

namespace LaravelEnso\Documents;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Documents\DynamicRelations\Documents;
use LaravelEnso\Documents\Observers\Observer;
use LaravelEnso\DynamicMethods\Services\Methods;

class DocumentableServiceProvider extends ServiceProvider
{
    protected array $register = [];

    public function boot()
    {
        Collection::wrap($this->register)
            ->each(function ($model) {
                Methods::bind($model, [Documents::class]);
                $model::observe(Observer::class);
            });
    }
}

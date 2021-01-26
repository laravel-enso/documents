<?php

namespace LaravelEnso\Documents\Services;

use LaravelEnso\Documents\DynamicRelations\Documents;
use LaravelEnso\Documents\Observers\Observer;
use LaravelEnso\DynamicMethods\Services\Methods;

class Register
{
    public static function handle(string $model)
    {
        Methods::bind($model, [Documents::class]);
        $model::observe(Observer::class);
    }
}

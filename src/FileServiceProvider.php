<?php

namespace LaravelEnso\Documents;

use LaravelEnso\Files\FileServiceProvider as ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public function folders(): array
    {
        return [];
    }
}

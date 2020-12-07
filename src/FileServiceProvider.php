<?php

namespace LaravelEnso\Documents;

use LaravelEnso\Documents\Models\Document;
use LaravelEnso\Files\FileServiceProvider as ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public function folders(): array
    {
        return ['documents' => [
            'model' => Document::morphMapKey(),
            'order' => 60,
        ]];
    }
}

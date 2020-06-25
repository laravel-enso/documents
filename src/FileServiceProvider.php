<?php

namespace LaravelEnso\Documents;

use LaravelEnso\Documents\Models\Document;
use LaravelEnso\Files\FileServiceProvider as ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->register['documents'] = [
            'model' => Document::morphMapKey(),
            'order' => 60,
        ];

        parent::boot();
    }
}

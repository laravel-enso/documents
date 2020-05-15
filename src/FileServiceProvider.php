<?php

namespace LaravelEnso\Documents;

use LaravelEnso\Files\FileServiceProvider as ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public $register = [
        'documents' => [
            'model' => 'document',
            'order' => 60,
        ],
    ];
}

<?php

namespace LaravelEnso\DocumentsManager\app\Observers;

use LaravelEnso\DocumentsManager\app\Classes\Destroyer;

class DocumentObserver
{
    public function deleting($document)
    {
        (new Destroyer($document))->run();
    }
}

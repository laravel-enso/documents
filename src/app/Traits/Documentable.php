<?php

namespace LaravelEnso\DocumentsManager\app\Traits;

use LaravelEnso\DocumentsManager\app\Models\Document;

trait Documentable
{
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}

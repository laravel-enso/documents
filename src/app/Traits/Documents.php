<?php

namespace LaravelEnso\DocumentsManager\app\Traits;

use LaravelEnso\DocumentsManager\app\Models\Document;

trait Documents
{
    public function documents()
    {
        return $this->hasMany(Document::class, 'created_by');
    }
}

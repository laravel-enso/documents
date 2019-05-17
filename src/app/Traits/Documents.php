<?php

namespace LaravelEnso\Documents\app\Traits;

use LaravelEnso\Documents\app\Models\Document;

trait Documents
{
    public function documents()
    {
        return $this->hasMany(Document::class, 'created_by');
    }
}

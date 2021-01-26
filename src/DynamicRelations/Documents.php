<?php

namespace LaravelEnso\Documents\DynamicRelations;

use Closure;
use LaravelEnso\Documents\Models\Document;
use LaravelEnso\DynamicMethods\Contracts\Method;

class Documents implements Method
{
    public function name(): string
    {
        return 'documents';
    }

    public function closure(): Closure
    {
        return fn () => $this->morphMany(Document::class, 'documentable');
    }
}

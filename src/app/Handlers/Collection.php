<?php

namespace LaravelEnso\DocumentsManager\app\Handlers;

use LaravelEnso\DocumentsManager\app\Models\Document;

class Collection
{
    private $type;
    private $id;

    public function __construct(string $type, int $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function data()
    {
        return Document::whereDocumentableType($this->documentable())
            ->whereDocumentableId($this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function documentable()
    {
        return (new ConfigMapper($this->type))->class();
    }
}

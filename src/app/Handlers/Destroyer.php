<?php

namespace LaravelEnso\DocumentsManager\app\Handlers;

use LaravelEnso\DocumentsManager\app\Models\Document;

class Destroyer extends Handler
{
    private $document;

    public function __construct(Document $document)
    {
        parent::__construct();

        $this->document = $document;
    }

    public function run()
    {
        \DB::transaction(function () {
            $this->document->delete();
            $this->fileManager->delete($this->document->saved_name);
        });
    }
}
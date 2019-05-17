<?php

namespace LaravelEnso\Documents\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Documents\app\Models\Document;
use LaravelEnso\Documents\app\Http\Requests\ValidateDocumentRequest;

class Store extends Controller
{
    public function __invoke(ValidateDocumentRequest $request, Document $document)
    {
        return $document->store(
            $request->validated(), $request->allFiles()
        );
    }
}

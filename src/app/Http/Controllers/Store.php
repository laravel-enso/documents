<?php

namespace LaravelEnso\Documents\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Documents\app\Models\Document;
use LaravelEnso\Documents\app\Http\Resources\Document as Resource;
use LaravelEnso\Documents\app\Http\Requests\ValidateDocumentRequest;

class Store extends Controller
{
    public function __invoke(ValidateDocumentRequest $request, Document $document)
    {
        $document->fill($request->validated());

        $this->authorize('store', $document);

        $documents = $document->store(
            $request->validated(), $request->allFiles()
        );

        $documents->each->load('file');

        return Resource::collection($documents);
    }
}

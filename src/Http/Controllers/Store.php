<?php

namespace LaravelEnso\Documents\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Documents\Http\Requests\ValidateDocument;
use LaravelEnso\Documents\Http\Resources\Document as Resource;
use LaravelEnso\Documents\Models\Document;

class Store extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateDocument $request, Document $document)
    {
        $document->fill($request->validated());

        $this->authorize('store', $document);

        $documents = $document->store(
            $request->validated(),
            $request->allFiles()
        );

        $documents->each->load('file');

        return Resource::collection($documents);
    }
}

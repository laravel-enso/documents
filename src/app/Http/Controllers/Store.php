<?php

namespace LaravelEnso\Documents\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Documents\App\Http\Requests\ValidateDocumentRequest;
use LaravelEnso\Documents\App\Http\Resources\Document as Resource;
use LaravelEnso\Documents\App\Models\Document;

class Store extends Controller
{
    use AuthorizesRequests;

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

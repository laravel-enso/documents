<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\DocumentsManager\app\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\DocumentsManager\app\Http\Resources\Document as Resource;
use LaravelEnso\DocumentsManager\app\Http\Requests\ValidateDocumentRequest;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function index(ValidateDocumentRequest $request)
    {
        return Resource::collection(
            Document::query()
                ->with('file.createdBy.avatar')
                ->for($request->validated())
                ->ordered()
                ->get()
        );
    }

    public function store(ValidateDocumentRequest $request, Document $document)
    {
        return $document->store(
            $request->validated(), $request->allFiles()
        );
    }

    public function share(Document $document)
    {
        return $document->download();
    }

    public function destroy(Document $document)
    {
        $this->authorize('destroy', $document);

        $document->delete();
    }
}

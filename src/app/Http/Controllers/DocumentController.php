<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelEnso\DocumentsManager\app\Models\Document;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        return Document::with('file')
            ->for($request->only(['documentable_id', 'documentable_type']))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request, Document $document)
    {
        return $document->store(
            $request->allFiles(),
            $request->only(['documentable_type', 'documentable_id'])
        );
    }

    public function show(Document $document)
    {
        $this->authorize('access', $document);

        return $document->inline();
    }

    public function download(Document $document)
    {
        $this->authorize('access', $document);

        return $document->download();
    }

    public function link(Document $document)
    {
        $this->authorize('access', $document);

        return ['link' => $document->temporaryLink()];
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

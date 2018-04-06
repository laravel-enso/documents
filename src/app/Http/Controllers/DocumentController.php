<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Handlers\ConfigMapper;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        return Document::whereDocumentableId($request->get('id'))
            ->whereDocumentableType((new ConfigMapper($request->get('type')))->class())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request, string $type, int $id)
    {
        return Document::create($request->allFiles(), $type, $id);
    }

    public function show(Document $document)
    {
        $this->authorize('download', $document);

        return $document->inline();
    }

    public function download(Document $document)
    {
        $this->authorize('download', $document);

        return $document->download();
    }

    public function destroy(Document $document)
    {
        $this->authorize('destroy', $document);

        $document->delete();
    }
}

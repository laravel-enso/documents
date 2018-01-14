<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Http\Services\DocumentService;

class DocumentController extends Controller
{
    public function index(string $type, int $id, DocumentService $service)
    {
        return $service->index($type, $id);
    }

    public function store(Request $request, string $type, int $id, DocumentService $service)
    {
        return $service->upload($request, $type, $id);
    }

    public function show(Document $document, DocumentService $service)
    {
        $this->authorize('download', $document);

        return $service->show($document);
    }

    public function download(Document $document, DocumentService $service)
    {
        $this->authorize('download', $document);

        return $service->download($document);
    }

    public function destroy(Document $document, DocumentService $service)
    {
        $this->authorize('destroy', $document);

        return $service->destroy($document);
    }
}

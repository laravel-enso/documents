<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Http\Services\DocumentService;

class DocumentController extends Controller
{
    private $service;

    public function __construct(DocumentService $service)
    {
        $this->service = $service;
    }

    public function index(string $type, int $id)
    {
        return $this->service->index($type, $id);
    }

    public function store(Request $request, string $type, int $id)
    {
        return $this->service->upload($request, $type, $id);
    }

    public function show(Document $document)
    {
        // $this->authorize('download', $document);//fixme

        return $this->service->show($document);
    }

    public function download(Document $document)
    {
        // $this->authorize('download', $document);//fixme

        return $this->service->download($document);
    }

    public function destroy(Document $document)
    {
        $this->authorize('destroy', $document);

        return $this->service->destroy($document);
    }
}

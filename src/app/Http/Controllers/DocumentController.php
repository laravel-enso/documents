<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\DocumentsManager\app\Http\Services\DocumentService;
use LaravelEnso\DocumentsManager\app\Models\Document;

class DocumentController extends Controller
{
    public function __construct(Request $request)
    {
        $this->documents = new DocumentService($request);
    }

    public function index(string $type, int $id)
    {
        return $this->documents->index();
    }

    public function store(string $type, int $id) //fixme. Find a cleaner way.
    {
        return $this->documents->upload();
    }

    public function show(Document $document)
    {
        $this->authorize('download', $document);

        return $this->documents->show($document);
    }

    public function download(Document $document)
    {
        $this->authorize('download', $document);

        return $this->documents->download($document);
    }

    public function destroy(Document $document)
    {
        $this->authorize('destroy', $document);

        return $this->documents->destroy($document);
    }
}

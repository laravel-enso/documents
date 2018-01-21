<?php

namespace LaravelEnso\DocumentsManager\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelEnso\DocumentsManager\app\Handlers\Storer;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Handlers\Destroyer;
use LaravelEnso\DocumentsManager\app\Handlers\Presenter;
use LaravelEnso\DocumentsManager\app\Handlers\Collection;

class DocumentController extends Controller
{
    public function index(string $type, int $id)
    {
        return (new Collection($type, $id))->data();
    }

    public function store(Request $request, string $type, int $id)
    {
        (new Storer($request->allFiles(), $type, $id))->run();
    }

    public function show(Document $document)
    {
        $this->authorize('download', $document);

        return (new Presenter($document))->inline();
    }

    public function download(Document $document)
    {
        $this->authorize('download', $document);

        return (new Presenter($document))->download();
    }

    public function destroy(Document $document)
    {
        $this->authorize('destroy', $document);

        (new Destroyer($document))->run();
    }
}

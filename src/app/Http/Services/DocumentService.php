<?php

namespace LaravelEnso\DocumentsManager\app\Http\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\FileManager\Classes\FileManager;

class DocumentService extends Controller
{
    private $request;
    private $fileManager;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->fileManager = new FileManager(config('laravel-enso.paths.files'));
    }

    public function index()
    {
        $class = request('type');

        return $this->getDocumentable()->documents;
    }

    public function show(Document $document)
    {
        $fileWrapper = $this->fileManager->getFile($document->saved_name);
        $fileWrapper->originalName = $document->original_name;

        return $fileWrapper->getInlineResponse();
    }

    public function upload()
    {
        \DB::transaction(function () {
            $files = $this->getFilesRequest();
            $this->fileManager->startUpload($files);
            $this->store();
            $this->fileManager->commitUpload();
        });

        return $this->fileManager->getStatus();
    }

    public function download(Document $document)
    {
        $fileWrapper = $this->fileManager->getFile($document->saved_name);
        $fileWrapper->originalName = $document->original_name;

        return $fileWrapper->getDownloadResponse();
    }

    public function destroy(Document $document)
    {
        \DB::transaction(function () use ($document) {
            $document->delete();
            $this->fileManager->delete($document->saved_name);
        });

        return $this->fileManager->getStatus();
    }

    private function store()
    {
        $documentsList = collect();

        $this->fileManager->uploadedFiles->each(function ($file) use (&$documentsList) {
            $documentsList->push(new Document($file));
        });

        $this->getDocumentable()->documents()->saveMany($documentsList);
    }

    private function getFilesRequest()
    {
        $request = request()->all();
        unset($request['id']);
        unset($request['type']);

        return $request;
    }

    private function getDocumentable()
    {
        return $this->getDocumentableClass()::find($this->request['id']);
    }

    private function getDocumentableClass()
    {
        $class = config('documents.documentables.'.$this->request['type']);

        if (!$class) {
            throw new \EnsoException(
                __('Current entity does not exist in documents.php config file: ').$this->request['type']
            );
        }

        return $class;
    }
}

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
    private $documentable;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->fileManager = new FileManager(config('laravel-enso.paths.files'), config('laravel-enso.paths.temp'));
    }

    public function index()
    {
        return $this->getDocumentable()->documents;
    }

    public function upload()
    {
        try {
            \DB::transaction(function () {
                $this->fileManager->startUpload($this->request->all());
                $this->store();
                $this->fileManager->commitUpload();
            });
        } catch (\Exception $e) {
            $this->fileManager->deleteTempFiles();
        }
    }

    public function show(Document $document)
    {
        return $this->fileManager->getInline($document->original_name, $document->saved_name);
    }

    public function download(Document $document)
    {
        return $this->fileManager->download($document->original_name, $document->saved_name);
    }

    public function destroy(Document $document)
    {
        \DB::transaction(function () use ($document) {
            $document->delete();
            $this->fileManager->delete($document->saved_name);
        });
    }

    private function store()
    {
        $documentsList = collect();

        $this->fileManager->getUploadedFiles()->each(function ($file) use (&$documentsList) {
            $documentsList->push(new Document($file));
        });

        $this->getDocumentable()->documents()->saveMany($documentsList);
    }

    private function getDocumentable()
    {
        return $this->documentable = $this->getDocumentableClass()::find($this->request->route()->parameter('id'));
    }

    private function getDocumentableClass()
    {
        $class = config('documents.documentables.'.$this->request->route()->parameter('type'));

        if (!$class) {
            throw new \EnsoException(
                __('Current entity does not exist in documents.php config file: ').$this->request['type']
            );
        }

        return $class;
    }
}

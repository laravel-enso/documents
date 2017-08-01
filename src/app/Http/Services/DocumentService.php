<?php

namespace LaravelEnso\DocumentsManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\FileManager\Classes\FileManager;
use LaravelEnso\ImageTransformer\Classes\ImageTransformer;

class DocumentService
{
    private $request;
    private $messages;
    private $fileManager;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->messages = collect();

        $this->fileManager = new FileManager(
            config('laravel-enso.paths.files'),
            config('laravel-enso.paths.temp')
        );
    }

    public function index()
    {
        return $this->getDocumentable()->documents;
    }

    public function upload()
    {
        try {
            \DB::transaction(function () {
                $files = $this->request->allFiles();
                $this->optimizeImages($files);
                $this->fileManager->startUpload($files);
                $this->store();
                $this->fileManager->commitUpload();
            });
        } catch (\Exception $e) {
            $this->fileManager->deleteTempFiles();
            throw $e;
        }
    }

    public function show(Document $document)
    {
        return $this->fileManager->getInline($document->saved_name);
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
        $documentable = $this->getDocumentable();
        $existingDocuments = $documentable->documents->pluck('original_name');

        $this->fileManager->getUploadedFiles()->each(function ($file) use (&$documentsList, $existingDocuments) {
            if ($existingDocuments->contains($file['original_name'])) {
                throw new \EnsoException($file['original_name'].' '.__('already exists for this Entity'));
            }

            $documentsList->push(new Document($file));
        });

        $documentable->documents()->saveMany($documentsList);
    }

    private function optimizeImages($files)
    {
        (new ImageTransformer($files))
            ->resize(config('documents.imageWidth'), config('documents.imageHeight'))
            ->optimize();
    }

    private function getDocumentable()
    {
        return $this->getDocumentableClass()::find($this->request->route()->parameter('id'));
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

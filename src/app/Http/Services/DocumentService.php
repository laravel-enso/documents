<?php

namespace LaravelEnso\DocumentsManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\FileManager\Classes\FileManager;
use LaravelEnso\ImageTransformer\Classes\ImageTransformer;

class DocumentService
{
    private $fileManager;

    public function __construct(Request $request)
    {
        $this->fileManager = new FileManager(
            config('enso.config.paths.files'),
            config('enso.config.paths.temp')
        );
    }

    public function index(string $type, int $id)
    {
        return $this->getDocumentable($type, $id)->documents;
    }

    public function upload(Request $request, string $type, int $id)
    {
        try {
            \DB::transaction(function () use ($request, $type, $id) {
                $files = $request->allFiles();
                $this->optimizeImages($files);
                $this->fileManager->startUpload($files);
                $this->store($type, $id);
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

    private function store(string $type, int $id)
    {
        $documentsList = collect();
        $documentable = $this->getDocumentable($type, $id);
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
            ->resize(config('enso.documents.imageWidth'), config('enso.documents.imageHeight'))
            ->optimize();
    }

    private function getDocumentable(string $type, int $id)
    {
        return $this->getDocumentableClass($type)::find($id);
    }

    private function getDocumentableClass(string $type)
    {
        $class = config('enso.documents.documentables.'.$type);

        if (!$class) {
            throw new \EnsoException(
                __('Current entity does not exist in documents.php config file').': '.$type
            );
        }

        return $class;
    }
}

<?php

namespace LaravelEnso\DocumentsManager\app\Http\Services;

use Illuminate\Http\Request;
use LaravelEnso\FileManager\app\Classes\FileManager;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\ImageTransformer\Classes\ImageTransformer;
use LaravelEnso\DocumentsManager\app\Exceptions\DocumentException;

class DocumentService
{
    private $fileManager;

    public function __construct()
    {
        $this->fileManager = (new FileManager(config('enso.config.paths.files')));
    }

    public function index(string $type, int $id)
    {
        return $this->getDocumentable($type, $id)->documents;
    }

    public function upload(Request $request, string $type, int $id)
    {
        $this->fileManager->tempPath(config('enso.config.paths.temp'));

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
        return $this->fileManager
            ->download($document->original_name, $document->saved_name);
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
        $documents = collect();
        $documentable = $this->getDocumentable($type, $id);
        $existingDocuments = $documentable->documents->pluck('original_name');

        $this->fileManager->uploadedFiles()
            ->each(function ($file) use ($documents, $existingDocuments) {
                if ($existingDocuments->contains($file['original_name'])) {
                    throw new DocumentException(__(
                        'File :file already exists for this entity',
                        ['file' => $file['original_name']]
                    ));
                }

                $documents->push(new Document($file));
            });

        $documentable->documents()->saveMany($documents);
    }

    private function optimizeImages($files)
    {
        (new ImageTransformer($files))
            ->resize(config('enso.documents.imageWidth'), config('enso.documents.imageHeight'))
            ->optimize();
    }

    private function getDocumentable(string $type, int $id)
    {
        return $this->getDocumentableType($type)::find($id);
    }

    private function getDocumentableType(string $type)
    {
        $class = config('enso.documents.documentables.'.$type);

        if (!$class) {
            throw new DocumentException(__(
                'Entity :entity does not exist in documents.php config file',
                ['entity' => $type]
            ));
        }

        return $class;
    }
}

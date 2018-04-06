<?php

namespace LaravelEnso\DocumentsManager\app\Handlers;

use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\ImageTransformer\app\Classes\ImageTransformer;
use LaravelEnso\DocumentsManager\app\Exceptions\DocumentException;

class Storer extends Handler
{
    private $files;
    private $documents;
    private $documentable;

    public function __construct(array $files, $type, $id)
    {
        parent::__construct();

        $this->files = $files;
        $this->documentable = $this->documentable($type, $id);

        $this->fileManager->tempPath(config('enso.config.paths.temp'));
    }

    public function run()
    {
        $this->upload();

        return $this->documents;
    }

    private function upload()
    {
        try {
            \DB::transaction(function () {
                $this->processImages();
                $this->fileManager->startUpload($this->files);
                $this->documents = $this->store();
                $this->fileManager->commitUpload();
            });
        } catch (\Exception $exception) {
            $this->fileManager->deleteTempFiles();
            throw $exception;
        }

        return $this->documents;
    }

    private function processImages()
    {
        collect($this->files)->each(function ($file) {
            $validator = \Validator::make(['file' => $file], ['file' => 'image']);
            if (!$validator->fails()) {
                (new ImageTransformer($file))
                    ->optimize();
            }
        });
    }

    private function store()
    {
        $existing = $this->existing();

        $documents = $this->fileManager->uploadedFiles()
            ->map(function ($file) use ($existing) {
                if ($existing->contains($file['original_name'])) {
                    throw new DocumentException(__(
                        'File :file already exists for this entity',
                        ['file' => $file['original_name']]
                    ));
                }

                return new Document($file);
            });

        $this->documentable->documents()
            ->saveMany($documents);

        return $documents;
    }

    private function documentable($type, $id)
    {
        $class = (new ConfigMapper($type))->class();

        return $class::find($id);
    }

    private function existing()
    {
        return $this->documentable
            ->documents
            ->pluck('original_name');
    }
}

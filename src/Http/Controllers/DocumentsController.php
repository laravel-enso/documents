<?php

namespace LaravelEnso\DocumentsManager\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\DocumentsManager\Document;
use LaravelEnso\FileManager\FileManager;

class DocumentsController extends Controller
{

    private $fileManager;

    public function __construct()
    {

        $this->fileManager = new FileManager(env('FILES_PATH'));
    }

    public function upload()
    {

        \DB::transaction(function () {

            $files = $this->getFilesRequest();
            $this->fileManager->startUpload($files);
            $this->storeDocuments();
            $this->fileManager->commitUpload();
        });

        return $this->fileManager->getStatus();
    }

    function list() {

        $class        = request('type');
        $documentable = $class::find(request('id'));

        return $documentable->documents;
    }

    /** Gives back the document inline
     * @param Document $document
     *
     * @return mixed
     */
    public function show(Document $document)
    {

        $fileWrapper = $this->fileManager->getFile($document->saved_name);

        $fileWrapper->originalName = $document->original_name;

        return $fileWrapper->getInlineResponse();
    }

    /** Gives the document as attachment
     * @param Document $document
     *
     * @return mixed
     */
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

    private function storeDocuments()
    {

        $class         = request('type');
        $documentable  = $class::find(request('id'));
        $documentsList = collect();

        $this->fileManager->uploadedFiles->each(function ($file) use (&$documentsList) {

            $documentsList->push(new Document($file));
        });

        $documentable->documents()->saveMany($documentsList);
    }

    private function getFilesRequest()
    {

        $request = request()->all();
        unset($request['id']);
        unset($request['type']);

        return $request;
    }
}

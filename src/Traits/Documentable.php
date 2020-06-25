<?php

namespace LaravelEnso\Documents\Traits;

use LaravelEnso\Documents\Exceptions\DocumentConflict;
use LaravelEnso\Documents\Models\Document;

trait Documentable
{
    public static function bootDocumentable()
    {
        self::deleting(fn ($model) => $model->attemptDocumentableDeletion());

        self::deleted(fn ($model) => $model->cascadeDocumentDeletion());
    }

    public function document()
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    private function attemptDocumentableDeletion()
    {
        if (
            config('enso.documents.onDelete') === 'restrict'
            && $this->documents()->first() !== null
        ) {
            throw DocumentConflict::delete();
        }
    }

    private function cascadeDocumentDeletion()
    {
        if (config('enso.documents.onDelete') === 'cascade') {
            $this->documents()->delete();
        }
    }
}

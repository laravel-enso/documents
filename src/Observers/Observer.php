<?php

namespace LaravelEnso\Documents\Observers;

use LaravelEnso\Documents\Exceptions\DocumentConflict;

class Observer
{
    private function deleting()
    {
        if (config('enso.documents.onDelete') === 'restrict'
            && $this->documents()->exists()) {
            throw DocumentConflict::delete();
        }
    }

    private function deleted()
    {
        if (config('enso.documents.onDelete') === 'cascade') {
            $this->documents()->delete();
        }
    }
}

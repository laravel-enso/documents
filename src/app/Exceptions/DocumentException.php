<?php

namespace LaravelEnso\Documents\app\Exceptions;

use LaravelEnso\Helpers\app\Exceptions\EnsoException;

class DocumentException extends EnsoException
{
    public function duplicates($files)
    {
        throw new static(__(
            'File(s) :files already uploaded for this entity',
            ['files' => $files]
        ));
    }
}

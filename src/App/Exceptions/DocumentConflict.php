<?php

namespace LaravelEnso\Documents\App\Exceptions;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class DocumentConflict extends ConflictHttpException
{
    public static function delete()
    {
        return new static(__('The entity has documents attached and cannot be deleted'));
    }
}

<?php

namespace LaravelEnso\DocumentsManager\app\Classes;

use LaravelEnso\DocumentsManager\app\Exceptions\DocumentConfigException;

class ConfigMapper
{
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function class()
    {
        $documentable = config('enso.documents.documentables.'.$this->type);

        if (!$documentable) {
            throw new DocumentConfigException(__(
                'Entity :entity does not exist in enso/documents.php config file',
                ['entity' => $this->type]
            ));
        }

        return $documentable;
    }
}

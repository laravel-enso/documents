<?php

namespace LaravelEnso\DocumentsManager\app\Traits;

trait Documents
{
	public function documents()
    {
        return $this->hasMany('LaravelEnso\DocumentsManager\app\Models\Document', 'created_by');
    }
}
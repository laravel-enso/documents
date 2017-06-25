<?php

namespace LaravelEnso\DocumentsManager\app\Traits;

trait Documentable
{
	public function documents()
	{
	    return $this->morphMany('LaravelEnso\DocumentsManager\app\Models\Document', 'documentable');
	}
}
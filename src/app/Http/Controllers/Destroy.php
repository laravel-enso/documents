<?php

namespace LaravelEnso\Documents\app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Documents\app\Models\Document;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Document $document)
    {
        $this->authorize('destroy', $document);

        $document->delete();
    }
}

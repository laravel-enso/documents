<?php

namespace LaravelEnso\Documents\App\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Documents\App\Http\Requests\ValidateDocumentRequest;
use LaravelEnso\Documents\App\Http\Resources\Document as Resource;
use LaravelEnso\Documents\App\Models\Document;

class Index extends Controller
{
    public function __invoke(ValidateDocumentRequest $request)
    {
        return Resource::collection(
            Document::with('file.createdBy.avatar')
                ->for($request->validated())
                ->filter($request->get('query'))
                ->ordered()
                ->get()
        );
    }
}

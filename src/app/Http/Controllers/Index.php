<?php

namespace LaravelEnso\Documents\app\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Documents\app\Http\Requests\ValidateDocumentRequest;
use LaravelEnso\Documents\app\Http\Resources\Document as Resource;
use LaravelEnso\Documents\app\Models\Document;

class Index extends Controller
{
    public function __invoke(ValidateDocumentRequest $request)
    {
        return Resource::collection(
            Document::with('file.createdBy.avatar')
                ->for($request->validated())
                ->ordered()
                ->get()
        );
    }
}

<?php

namespace LaravelEnso\Documents\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Documents\Http\Requests\ValidateDocument;
use LaravelEnso\Documents\Http\Resources\Document as Resource;
use LaravelEnso\Documents\Models\Document;

class Index extends Controller
{
    public function __invoke(ValidateDocument $request)
    {
        return Resource::collection(
            Document::latest()
                ->with('file.createdBy.avatar')
                ->for($request->validated())
                ->filter($request->get('query'))
                ->get()
        );
    }
}

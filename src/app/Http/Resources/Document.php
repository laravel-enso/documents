<?php

namespace LaravelEnso\DocumentsManager\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\FileManager\app\Http\Resources\File;

class Document extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file' => new File($this->whenLoaded('file')),
            'isDeletable' => $this->isDeletable(),
            'createdAt' => $this->created_at->toDatetimeString(),
        ];
    }
}

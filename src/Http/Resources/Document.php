<?php

namespace LaravelEnso\Documents\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\Files\Http\Resources\File;

class Document extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file' => new File($this->whenLoaded('file')),
            'createdAt' => $this->created_at,
        ];
    }
}

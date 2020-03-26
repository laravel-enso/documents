<?php

namespace LaravelEnso\Documents\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\Files\App\Http\Resources\File;

class Document extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file' => new File($this->whenLoaded('file')),
            'isDestroyable' => $this->destroyableBy($request->user()),
            'isViewable' => $this->viewableBy($request->user()),
            'isShareable' => $this->shareableBy($request->user()),
            'createdAt' => $this->created_at,
        ];
    }
}

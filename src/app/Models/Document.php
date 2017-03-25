<?php

namespace LaravelEnso\DocumentsManager\App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['original_name', 'saved_name', 'size'];

    public function documentable()
    {
        return $this->morphTo();
    }
}
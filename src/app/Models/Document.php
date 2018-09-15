<?php

namespace LaravelEnso\DocumentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\FileManager\app\Traits\HasFile;
use LaravelEnso\ActivityLog\app\Traits\LogActivity;
use LaravelEnso\FileManager\app\Contracts\Attachable;
use LaravelEnso\FileManager\app\Contracts\VisibleFile;
use LaravelEnso\DocumentsManager\app\Classes\ConfigMapper;
use LaravelEnso\DocumentsManager\app\Exceptions\DocumentException;

class Document extends Model implements Attachable, VisibleFile
{
    use HasFile, LogActivity, CreatedBy;

    protected $optimizeImages = true;

    protected $fillable = ['name'];

    protected $loggableLabel = 'name';

    public function documentable()
    {
        return $this->morphTo();
    }

    public function isDeletable()
    {
        return request()->user()->can('destroy', $this);
    }

    public function store(array $files, $request)
    {
        $owner = (new ConfigMapper($request['documentable_type']))
                    ->model($request['documentable_id']);

        $existing = $owner->load('documents.file')
            ->documents->map(function ($document) {
                return $document->file->original_name;
            });

        \DB::transaction(function () use ($owner, $files, $existing) {
            collect($files)->each(function ($file) use ($owner, $existing) {
                if ($existing->contains($file->getClientOriginalName())) {
                    throw new DocumentException(__(
                        'File :file already exists for this entity',
                        ['file' => $file->getClientOriginalName()]
                    ));
                }

                $owner->documents()->create()
                    ->upload($file);
            });
        });
    }

    public function scopeFor($query, array $request)
    {
        $query->whereDocumentableId($request['documentable_id'])
            ->whereDocumentableType(
                (new ConfigMapper($request['documentable_type']))
                    ->class()
            );
    }

    public function scopeOrdered($query)
    {
        $query->orderBy('created_at', 'desc');
    }

    public function getLoggableMorph()
    {
        return config('enso.documents.loggableMorph');
    }

    public function resizeImages()
    {
        return [
            config('enso.documents.imageWidth'),
            config('enso.documents.imageHeight'),
        ];
    }

    public function folder()
    {
        return config('enso.config.paths.files');
    }
}

<?php

namespace LaravelEnso\Documents\app\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Files\app\Traits\HasFile;
use LaravelEnso\Files\app\Traits\FilePolicies;
use LaravelEnso\Files\app\Contracts\Attachable;
use LaravelEnso\Helpers\app\Traits\UpdatesOnTouch;
use LaravelEnso\Files\app\Exceptions\FileException;
use Illuminate\Database\Eloquent\Relations\Relation;
use LaravelEnso\Files\app\Contracts\AuthorizesFileAccess;

class Document extends Model implements Attachable, AuthorizesFileAccess
{
    use FilePolicies, HasFile, UpdatesOnTouch;

    protected $fillable = ['documentable_type', 'documentable_id'];

    protected $touches = ['documentable'];

    protected $optimizeImages = true;

    public function documentable()
    {
        return $this->morphTo();
    }

    public function store(array $request, array $files)
    {
        $documents = collect();

        $class = Relation::getMorphedModel($request['documentable_type'])
            ?? $request['documentable_type'];

        $documentable = $class::query()->find($request['documentable_id']);

        $existing = $documentable->load('documents.file')
            ->documents
            ->map(fn($document) => $document->file->original_name);

        DB::transaction(function () use ($documents, $documentable, $files, $existing) {
            $conflictingFiles = collect($files)
                ->map(fn($file) => $file->getClientOriginalName())
                ->intersect($existing);

            if ($conflictingFiles->isNotEmpty()) {
                throw FileException::duplicates($conflictingFiles->implode(', '));
            }

            collect($files)->each(function ($file) use ($documents, $documentable) {
                $document = $documentable->documents()->create();
                $document->upload($file);
                $documents->push($document);
            });
        });

        return $documents;
    }

    public function scopeFor($query, array $params)
    {
        $query->whereDocumentableId($params['documentable_id'])
            ->whereDocumentableType($params['documentable_type']);
    }

    public function scopeOrdered($query)
    {
        $query->orderByDesc('created_at');
    }

    public function getLoggableMorph()
    {
        return config('enso.documents.loggableMorph');
    }

    public function resizeImages(): array
    {
        return [
            'width' => config('enso.documents.imageWidth'),
            'height' => config('enso.documents.imageHeight'),
        ];
    }
}

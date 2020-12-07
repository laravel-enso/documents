<?php

namespace LaravelEnso\Documents\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Documents\Contracts\Ocrable;
use LaravelEnso\Documents\Jobs\Ocr as Job;
use LaravelEnso\Files\Contracts\Attachable;
use LaravelEnso\Files\Contracts\AuthorizesFileAccess;
use LaravelEnso\Files\Exceptions\File;
use LaravelEnso\Files\Traits\FilePolicies;
use LaravelEnso\Files\Traits\HasFile;
use LaravelEnso\Helpers\Traits\CascadesMorphMap;
use LaravelEnso\Helpers\Traits\UpdatesOnTouch;

class Document extends Model implements Attachable, AuthorizesFileAccess
{
    use CascadesMorphMap, FilePolicies, HasFile, UpdatesOnTouch;

    protected $guarded = ['id'];

    protected $touches = ['documentable'];

    protected $folder = 'files';

    protected $optimizeImages = true;

    public function documentable()
    {
        return $this->morphTo();
    }

    public function store(array $request, array $files)
    {
        $documents = new Collection();

        $class = Relation::getMorphedModel($request['documentable_type'])
            ?? $request['documentable_type'];

        $documentable = $class::query()->find($request['documentable_id']);

        $this->validateExisting($files, $documentable);

        DB::transaction(fn () => (new Collection($files))
            ->each(fn ($file) => $documents->push($this->storeFile($documentable, $file))));

        return $documents;
    }

    public function scopeFor(Builder $query, array $params): Builder
    {
        return $query->whereDocumentableId($params['documentable_id'])
            ->whereDocumentableType($params['documentable_type']);
    }

    public function scopeFilter(Builder $query, ?string $search): Builder
    {
        return $query->when($search, fn ($query) => $query
            ->where(fn ($query) => $query
                ->whereHas('file', fn ($file) => $file
                    ->where('original_name', 'LIKE', '%'.$search.'%'))
                ->orWhere('text', 'LIKE', '%'.$search.'%')));
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

    private function ocr($document)
    {
        if ($this->ocrable($document)) {
            Job::dispatch($document);
        }

        return $this;
    }

    private function ocrable($document)
    {
        return $document->documentable instanceof Ocrable
            && $document->file->mime_type === 'application/pdf';
    }

    private function validateExisting(array $files, $documentable): void
    {
        $existing = $documentable->load('documents.file')
            ->documents->map(fn ($document) => $document->file->original_name);

        $conflictingFiles = (new Collection($files))
            ->map(fn ($file) => $file->getClientOriginalName())
            ->intersect($existing);

        if ($conflictingFiles->isNotEmpty()) {
            throw File::duplicates($conflictingFiles->implode(', '));
        }
    }

    private function storeFile($documentable, UploadedFile $file)
    {
        $document = $documentable->documents()->create();
        $document->file->upload($file);
        $this->ocr($document);

        return $document;
    }
}

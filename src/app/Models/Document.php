<?php

namespace LaravelEnso\Documents\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Documents\app\Contracts\Ocrable;
use LaravelEnso\Documents\app\Jobs\Ocr as OcrJob;
use LaravelEnso\Files\app\Contracts\Attachable;
use LaravelEnso\Files\app\Contracts\AuthorizesFileAccess;
use LaravelEnso\Files\app\Exceptions\File;
use LaravelEnso\Files\app\Traits\FilePolicies;
use LaravelEnso\Files\app\Traits\HasFile;
use LaravelEnso\Helpers\app\Traits\UpdatesOnTouch;

class Document extends Model implements Attachable, AuthorizesFileAccess
{
    use FilePolicies, HasFile, UpdatesOnTouch;

    protected $fillable = ['documentable_type', 'documentable_id', 'text'];

    protected $touches = ['documentable'];

    protected $optimizeImages = true;

    public function documentable()
    {
        return $this->morphTo();
    }

    //TODO refactor
    public function store(array $request, array $files)
    {
        $documents = collect();

        $class = Relation::getMorphedModel($request['documentable_type'])
            ?? $request['documentable_type'];

        $documentable = $class::query()->find($request['documentable_id']);

        $this->validateExisting($files, $documentable);

        DB::transaction(function () use ($documents, $documentable, $files) {
            collect($files)->each(function ($file) use ($documents, $documentable) {
                $document = $documentable->documents()->create();
                $document->upload($file);
                $this->ocr($document);
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

    public function scopeFilter($query, $search)
    {
        if (! empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('file', function ($query) use ($search) {
                    $query->where('original_name', 'LIKE', '%'.$search.'%');
                })->orWhere('text', 'LIKE', '%'.$search.'%');
            });
        }
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
            OcrJob::dispatch($document);
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

        $conflictingFiles = collect($files)
            ->map(fn ($file) => $file->getClientOriginalName())
            ->intersect($existing);

        if ($conflictingFiles->isNotEmpty()) {
            throw File::duplicates($conflictingFiles->implode(', '));
        }
    }
}

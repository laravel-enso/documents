<?php

namespace LaravelEnso\Documents\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelEnso\Documents\app\Models\Document;

class Ocr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $document;

    public $queue;

    public function __construct(Document $document)
    {
        $this->document = $document;

        $this->queue = config('enso.documents.queues.ocr');
    }

    public function handle()
    {
        $text = (new self($this->document->file->path()))->text();

        $this->document->update([
            'text' => preg_replace('/\s+/', ' ', $text),
        ]);
    }
}

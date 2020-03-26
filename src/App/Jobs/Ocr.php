<?php

namespace LaravelEnso\Documents\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelEnso\Documents\App\Models\Document;
use LaravelEnso\Ocr\Ocr as Service;

class Ocr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue;

    private Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;

        $this->queue = config('enso.documents.queues.ocr');
    }

    public function handle()
    {
        $text = (new Service($this->document->file->path()))->text();

        $this->document->update([
            'text' => preg_replace('/\s+/', ' ', $text),
        ]);
    }
}

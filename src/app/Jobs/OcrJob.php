<?php

namespace LaravelEnso\Documents\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelEnso\Documents\app\Models\Document;
use LaravelEnso\Ocr\Ocr;

class OcrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $queue;

    private $docuemnt;

    public function __construct(Document $docuemnt)
    {
        $this->docuemnt = $docuemnt;

        $this->queue = config('enso.documents.queues.ocr');
    }

    public function handle()
    {
        $text = (new Ocr($this->docuemnt->file->path()))->text();

        $this->docuemnt->update([
            'text' => preg_replace('/\s+/', ' ', $text),
        ]);
    }
}

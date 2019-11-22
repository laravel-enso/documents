<?php

namespace LaravelEnso\Documents\app\Jobs;

use LaravelEnso\Ocr\Ocr;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use LaravelEnso\Documents\app\Models\Document;

class OcrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $docuemnt;

    public $queue;

    public function __construct(Document $docuemnt)
    {
        $this->docuemnt = $docuemnt;

        $this->queue = config('enso.documents.queues.ocr');
    }

    public function handle()
    {
        $text = (new Ocr($this->docuemnt->file->path()))->text();

        $this->docuemnt->update([
            'text' => preg_replace('/\s+/', ' ', $text)
        ]);
    }
}

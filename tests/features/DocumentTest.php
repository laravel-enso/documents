<?php

use App\Owner;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\TestHelper\app\Classes\TestHelper;

class DocumentTest extends TestHelper
{
    use DatabaseMigrations;

    private $owner;

    protected function setUp()
    {
        parent::setUp();

        // $this->disableExceptionHandling();
        config()->set('laravel-enso.paths.files', 'testFolder');
        $this->owner = Owner::first();
        $this->signIn(User::first());
    }

    /** @test */
    public function index()
    {
        $this->get('/core/documents/owner/'.$this->owner->id)
            ->assertStatus(200);
    }

    /** @test */
    public function upload()
    {
        $document = $this->uploadDocument();
        $this->assertNotNull($document);
        Storage::assertExists('testFolder/'.$document->saved_name);

        $this->cleanUp();
    }

    /** @test */
    public function show()
    {
        $document = $this->uploadDocument();

        $this->get('/core/documents/show/'.$document->id)
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function download()
    {
        $document = $this->uploadDocument();

        $this->get('/core/documents/download/'.$document->id)
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function destroy()
    {
        $document = $this->uploadDocument();

        $this->delete('/core/documents/destroy/'.$document->id)
            ->assertStatus(200);

        Storage::assertMissing('testFolder/'.$document->saved_name);
        $this->assertNull($document->fresh());

        $this->cleanUp();
    }

    private function uploadDocument()
    {
        $this->post('/core/documents/upload/owner/'.$this->owner->id, [
            'file' => UploadedFile::fake()->create('document.doc'),
        ]);

        return $this->owner->documents->first();
    }

    private function cleanUp()
    {
        Storage::deleteDirectory('testFolder');
    }
}

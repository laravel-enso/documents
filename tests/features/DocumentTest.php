<?php

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase, SignIn;

    private $owner;

    protected function setUp()
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
        config()->set('enso.config.paths.files', 'testFolder');
        $this->owner = config('enso.config.ownerModel')::first();
        $this->signIn(User::first());
    }

    /** @test */
    public function index()
    {
        $this->get(route('core.documents.index', [
            'type' => 'owner', 'id' => $this->owner->id
        ], false))
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

        $this->get(route('core.documents.show', $document->id, false))
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function download()
    {
        $document = $this->uploadDocument();

        $this->get(route('core.documents.download', $document->id, false))
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function destroy()
    {
        $document = $this->uploadDocument();

        $this->delete(route('core.documents.destroy', $document->id, false))
            ->assertStatus(200);

        Storage::assertMissing('testFolder/'.$document->saved_name);
        $this->assertNull($document->fresh());

        $this->cleanUp();
    }

    private function uploadDocument()
    {
        $this->post(route('core.documents.store', ['owner', $this->owner->id], false), [
            'file' => UploadedFile::fake()->create('document.doc'),
        ]);

        return $this->owner->documents->first();
    }

    private function cleanUp()
    {
        Storage::deleteDirectory('testFolder');
    }
}

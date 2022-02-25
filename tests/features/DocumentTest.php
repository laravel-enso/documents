<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Documents\Traits\Documentable;
use LaravelEnso\Files\Contracts\Attachable;
use LaravelEnso\Files\Models\File;
use LaravelEnso\Users\Models\User;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private DocumentTestModel $testModel;
    private string $testFolder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());

        $this->testModel = $this->model();
        $this->testFolder = Config::get('enso.files.testingFolder');
    }

    public function tearDown(): void
    {
        $this->cleanUp();
        parent::tearDown();
    }

    /** @test */
    public function can_get_documents_index()
    {
        $this->get(route('core.documents.index', [
            'documentable_type' => $this->testModel::class,
            'documentable_id' => $this->testModel->id,
        ], false))->assertStatus(200);
    }

    /** @test */
    public function can_upload_document()
    {
        $this->post(route('core.documents.store'), [
            'documentable_type' => $this->testModel::class,
            'documentable_id' => $this->testModel->id,
            'file' => UploadedFile::fake()->create('document.doc'),
        ]);

        $document = $this->testModel->documents()
            ->with('file')
            ->first();

        Storage::assertExists($document->file->path());
    }

    /** @test */
    public function can_display_document()
    {
        $document = $this->testModel->documents()->create();
        $uploadedFile = UploadedFile::fake()->create('document.doc');

        $file = File::upload($document, $uploadedFile);
        $document->file()->associate($file)->save();

        $this->get(route('core.files.show', $file->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function can_download_document()
    {
        $document = $this->testModel->documents()->create();
        $uploadedFile = UploadedFile::fake()->create('document.doc');

        $file = File::upload($document, $uploadedFile);
        $document->file()->associate($file)->save();

        $this->get(route('core.files.download', $file->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function can_destroy_document()
    {
        $document = $this->testModel->documents()->create();
        $uploadedFile = UploadedFile::fake()->create('document.doc');

        $file = File::upload($document, $uploadedFile);
        $document->file()->associate($file)->save();

        $document = $this->testModel->documents()
            ->with('file')
            ->first();

        $this->delete(route('core.documents.destroy', [$this->testModel->id], false))
            ->assertStatus(200);

        Storage::assertMissing($file->path());
        $this->assertNull($document->fresh());
    }

    private function cleanUp()
    {
        Storage::deleteDirectory($this->testFolder);
    }

    private function model()
    {
        $this->createTestTable();

        return DocumentTestModel::create(['name' => 'documentable']);
    }

    private function createTestTable(): self
    {
        Schema::create('document_test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        return $this;
    }
}

class DocumentTestModel extends Model implements Attachable
{
    use Documentable;

    protected $fillable = ['name'];

    public function file(): Relation
    {
        return $this->belongsTo(File::class);
    }
}

<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Documents\Traits\Documentable;
use LaravelEnso\Files\Contracts\Attachable;
use LaravelEnso\Users\Models\User;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());

        $this->testModel = $this->model();
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

        $document = $this->testModel->documents()->first();

        Storage::assertExists($document->file->path);
    }

    /** @test */
    public function can_display_document()
    {
        $this->testModel->file->upload(UploadedFile::fake()->create('document.doc'));

        $this->get(route('core.files.show', $this->testModel->file->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function can_download_document()
    {
        $this->testModel->file->upload(UploadedFile::fake()->create('document.doc'));

        $this->get(route('core.files.download', $this->testModel->file->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function can_destroy_document()
    {
        $this->testModel->documents()
            ->create()
            ->file->upload(UploadedFile::fake()->create('document.doc'));

        $document = $this->testModel->documents()
            ->with('file')
            ->first();

        $this->delete(route('core.documents.destroy', [$this->testModel->id], false))
            ->assertStatus(200);

        Storage::assertMissing($document->file->path);

        $this->assertNull($document->fresh());
    }

    private function cleanUp()
    {
        Storage::deleteDirectory(Config::get('enso.files.testingFolder'));
    }

    private function model()
    {
        $this->createTestTable();

        return DocumentTestModel::create(['name' => 'documentable']);
    }

    private function createTestTable()
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
}

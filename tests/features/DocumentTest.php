<?php

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\FileManager\app\Traits\HasFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\FileManager\app\Classes\FileManager;
use LaravelEnso\FileManager\app\Contracts\Attachable;
use LaravelEnso\DocumentsManager\app\Traits\Documentable;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private $testModel;

    protected function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

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
            'documentable_type' => get_class($this->testModel),
            'documentable_id' => $this->testModel->id
        ], false))->assertStatus(200);
    }

    /** @test */
    public function can_upload_document()
    {
        $this->post(route('core.documents.store'), [
            'documentable_type' => get_class($this->testModel),
            'documentable_id' => $this->testModel->id,
            'file' => UploadedFile::fake()->create('document.doc'),
        ]);

        $filename = $this->testModel->documents()->first()->file->saved_name;

        Storage::assertExists(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$filename
        );
    }

    /** @test */
    public function can_display_document()
    {
        $this->testModel->upload(UploadedFile::fake()->create('document.doc'));

        $this->get(route('core.files.show', $this->testModel->file->id, false))
            ->assertStatus(200);
    }

    /** @test */
    public function can_download_document()
    {
        $this->testModel->upload(UploadedFile::fake()->create('document.doc'));

        $this->get(route('core.files.download', $this->testModel->file->id, false))
            ->assertStatus(200)
            ->assertHeader(
                'content-disposition',
                'attachment; filename='.$this->testModel->file->original_name
            );
    }

    /** @test */
    public function can_destroy_document()
    {
        $this->testModel->documents()
            ->create()
            ->upload(UploadedFile::fake()->create('document.doc'));

        $document = $this->testModel->documents()
            ->first();

        $filename = $document->file->saved_name;

        $this->delete(route('core.documents.destroy', [$this->testModel->id], false))
            ->assertStatus(200);

        Storage::assertMissing(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$filename
        );

        $this->assertNull($document->fresh());
    }

    private function cleanUp()
    {
        \Storage::deleteDirectory(FileManager::TestingFolder);
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
    use HasFile, Documentable;

    protected $fillable = ['name'];
}

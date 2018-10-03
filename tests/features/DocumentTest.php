<?php

use LaravelEnso\Core\app\Models\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use LaravelEnso\FileManager\app\Classes\FileManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\DocumentsManager\app\Traits\Documentable;

class DocumentTest extends TestCase
{
    use RefreshDatabase, SignIn;

    private $documentTestModel;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->seed()
            ->createDocumentTestModelsTable()
            ->signIn(User::first());

        $this->documentTestModel = $this->createDocumentTestModel();

        config([
            'enso.documents.documentables' => ['documentTestModel' => 'DocumentTestModel']
        ]);
    }

    /** @test */
    public function index()
    {
        $this->get(route('core.documents.index', [
            'documentable_type' => 'documentTestModel',
            'documentable_id' => $this->documentTestModel->id
        ], false))->assertStatus(200);
    }

    /** @test */
    public function upload()
    {
        $document = $this->uploadDocument();

        $this->assertNotNull($document);

        Storage::assertExists(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$document->file->saved_name
        );

        $this->cleanUp();
    }

    /** @test */
    public function show()
    {
        $document = $this->uploadDocument();

        $this->get(route('core.files.show', $document->file->id, false))
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function download()
    {
        $document = $this->uploadDocument();

        $this->get(route('core.files.download', $document->file->id, false))
            ->assertStatus(200);

        $this->cleanUp();
    }

    /** @test */
    public function destroy()
    {
        $document = $this->uploadDocument();

        $filename = $document->file->saved_name;

        $this->delete(route('core.documents.destroy', $document->id, false))
            ->assertStatus(200);

        Storage::assertMissing(
            FileManager::TestingFolder.DIRECTORY_SEPARATOR.$filename
        );

        $this->assertNull($document->fresh());

        $this->cleanUp();
    }

    private function uploadDocument()
    {
        $this->post(route('core.documents.store'), [
            'documentable_type' => 'documentTestModel',
            'documentable_id' => $this->documentTestModel->id,
            'file' => UploadedFile::fake()->create('document.doc'),
        ]);

        return $this->documentTestModel->documents->first();
    }

    private function cleanUp()
    {
        \Storage::deleteDirectory(FileManager::TestingFolder);
    }

    private function createDocumentTestModelsTable()
    {
        Schema::create('document_test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        return $this;
    }

    private function createDocumentTestModel()
    {
        return DocumentTestModel::create(['name' => 'documentable']);
    }
}

class DocumentTestModel extends Model
{
    use Documentable;

    protected $fillable = ['name'];
}

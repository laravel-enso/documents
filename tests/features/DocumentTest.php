<?php

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\TestHelper\app\Traits\SignIn;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\DocumentsManager\app\Traits\Documentable;

class DocumentTest extends TestCase
{
    use RefreshDatabase, SignIn;

    private $documentTestModel;

    protected function setUp()
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
        config()->set('enso.config.paths.files', 'testFolder');
        $this->signIn(User::first());

        $this->createDocumentTestModelsTable();
        $this->documentTestModel = $this->createDocumentTestModel();

        config(['enso.documents.documentables' => ['documentTestModel' => 'DocumentTestModel']]);
    }

    /** @test */
    public function index()
    {
        $this->get(route('core.documents.index', [
            'documentable_type' => 'documentTestModel', 'documentable_id' => $this->documentTestModel->id
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
        $this->post(route('core.documents.store'), [
            'documentable_type' => 'documentTestModel',
            'documentable_id' => $this->documentTestModel->id,
            'file' => UploadedFile::fake()->create('document.doc'),
        ]);

        return $this->documentTestModel->documents->first();
    }

    private function cleanUp()
    {
        Storage::deleteDirectory('testFolder');
    }

    private function createDocumentTestModelsTable()
    {
        Schema::create('document_test_models', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
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

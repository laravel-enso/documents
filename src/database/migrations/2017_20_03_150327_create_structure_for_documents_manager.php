<?php

use Illuminate\Database\Migrations\Migration;
use LaravelEnso\Core\App\Classes\StructureManager\StructureSupport;

class CreateStructureForDocumentsManager extends Migration
{
    use StructureSupport;

    private $permissionsGroup = [
        'name' => 'system.documents', 'description' => 'Documents Permissions Group',
    ];

    private $permissions = [
        ['name' => 'core.documents.upload', 'description' => 'Upload Docs', 'type' => 1],
        ['name' => 'core.documents.list', 'description' => 'List Documents for Documentable', 'type' => 0],
        ['name' => 'core.documents.show', 'description' => 'Show Document', 'type' => 0],
        ['name' => 'core.documents.download', 'description' => 'Download Document', 'type' => 0],
        ['name' => 'core.documents.destroy', 'description' => 'Delete Document', 'type' => 1],
    ];
}
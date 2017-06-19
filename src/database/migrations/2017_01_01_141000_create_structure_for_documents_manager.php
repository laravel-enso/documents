<?php

use LaravelEnso\Core\app\Classes\StructureManager\StructureMigration;

class CreateStructureForDocumentsManager extends StructureMigration
{
    protected $permissionsGroup = [
        'name' => 'core.documents', 'description' => 'Documents Permissions Group',
    ];

    protected $permissions = [
        ['name' => 'core.documents.upload', 'description' => 'Upload Docs', 'type' => 1, 'default' => false],
        ['name' => 'core.documents.index', 'description' => 'List Documents for Documentable', 'type' => 0, 'default' => false],
        ['name' => 'core.documents.show', 'description' => 'Show Document', 'type' => 0, 'default' => false],
        ['name' => 'core.documents.download', 'description' => 'Download Document', 'type' => 0, 'default' => false],
        ['name' => 'core.documents.destroy', 'description' => 'Delete Document', 'type' => 1, 'default' => false]
    ];
}

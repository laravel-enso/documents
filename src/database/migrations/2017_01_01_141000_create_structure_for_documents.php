<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForDocuments extends Migration
{
    protected $permissions = [
        ['name' => 'core.documents.store', 'description' => 'Upload documents', 'type' => Types::Write, 'is_default' => false],
        ['name' => 'core.documents.index', 'description' => 'List documents for documentable', 'type' => Types::Read, 'is_default' => false],
        ['name' => 'core.documents.destroy', 'description' => 'Delete document', 'type' => Types::Write, 'is_default' => false],
    ];
}

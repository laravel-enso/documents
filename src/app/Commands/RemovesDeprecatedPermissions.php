<?php

namespace LaravelEnso\DocumentsManager\app\Commands;

use Illuminate\Console\Command;
use LaravelEnso\PermissionManager\app\Models\Permission;

class RemovesDeprecatedPermissions extends Command
{
    private const Permissions = [
        'core.documents.show', 'core.documents.download', 'core.documents.link',
    ];

    protected $signature = 'enso:documents:remove-deprecated-permissions';

    protected $description = 'Removes unused permissions for documents';

    public function handle()
    {
        Permission::whereIn('name', self::Permissions)->delete();

        $this->info('Deprecated permissions were successfully removed');
    }
}

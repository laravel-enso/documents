<?php

namespace LaravelEnso\DocumentsManager\app\Commands;

use Illuminate\Console\Command;
use LaravelEnso\RoleManager\app\Models\Role;
use LaravelEnso\PermissionManager\app\Models\Permission;
use LaravelEnso\PermissionManager\app\Models\PermissionGroup;

class UpdateDocumentsPermissions extends Command
{
    protected $signature = 'enso:update-documents-permissions';

    protected $description = 'This command will add the new core.documents.link permission';

    public function handle()
    {
        $exists = Permission::whereName('core.documents.link')
            ->first();

        if ($exists) {
            $this->info('Permissions already updated.');

            return;
        }

        $groupId = PermissionGroup::whereName('core.documents')
            ->first()
            ->id;

        (Permission::create([
            'permission_group_id' => $groupId,
            'name' => 'core.documents.link',
            'description' => 'Get document download temporary link',
            'type' => 0,
            'is_default' => false,
        ]))->roles()
            ->attach(
                Role::whereName(config('enso.config.defaultRole'))
                    ->first()
            );

        $this->info('Permissions were successfully updated.');
    }
}

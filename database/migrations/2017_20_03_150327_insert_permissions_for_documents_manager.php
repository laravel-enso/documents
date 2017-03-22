<?php

use Illuminate\Database\Migrations\Migration;
use LaravelEnso\Core\Models\Permission;
use LaravelEnso\Core\Models\PermissionsGroup;
use LaravelEnso\Core\Models\Role;

class InsertPermissionsForDocumentsManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissionsGroup = PermissionsGroup::whereName('core.documents')->first();

        if ($permissionsGroup) {
            return;
        }

        \DB::transaction(function () {
            $permissionsGroup = new PermissionsGroup([
                'name'        => 'core.documents',
                'description' => 'Documents Permissions Group',
            ]);

            $permissionsGroup->save();

            $permissions = [
                [
                    'name'        => 'core.documents.upload',
                    'description' => 'Upload Docs',
                    'type'        => 1,
                ],
                [
                    'name'        => 'core.documents.list',
                    'description' => 'List Documents for Documentable',
                    'type'        => 0,
                ],
                [
                    'name'        => 'core.documents.show',
                    'description' => 'Show Document',
                    'type'        => 0,
                ],
                [
                    'name'        => 'core.documents.download',
                    'description' => 'Download Document',
                    'type'        => 0,
                ],
                [
                    'name'        => 'core.documents.destroy',
                    'description' => 'Delete Document',
                    'type'        => 1,
                ],
            ];

            $adminRole = Role::whereName('admin')->first();

            foreach ($permissions as $permission) {
                $permission = new Permission($permission);
                $permissionsGroup->permissions()->save($permission);
                $adminRole->permissions()->save($permission);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::transaction(function () {
            $permissionsGroup = PermissionsGroup::whereName('core.documents')->first();
            $permissionsGroup->permissions->each->delete();
            $permissionsGroup->delete();
        });
    }
}

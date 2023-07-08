<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Traits\{DisableForeignKeys};

class PermissionRoleTableSeeder extends Seeder
{

    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        // creating super admin/ users role
        $superAdminAccess = Role::updateOrCreate(['name' => config('access.users.admin_role')]);
        $userAccess       = Role::updateOrCreate(['name' => config('access.users.default_role')]);

        // All permissions
        $permissions = [
            'view users'
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // give permission to super admin
        $superAdminAccess->givePermissionTo([
            'view users'
        ]);

        $this->enableForeignKeys();

    }
}

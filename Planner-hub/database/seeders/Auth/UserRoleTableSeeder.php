<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Http\Traits\{DisableForeignKeys};

/**
 * Class UserRoleTableSeeder.
 */
class UserRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::where('email', 'admin@admin.com')->first()->assignRole(config('access.users.admin_role'));

        $this->enableForeignKeys();
    }
}

<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Http\Traits\{DisableForeignKeys};
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator
        if(!User::where('email','admin@admin.com')->first()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => '1111111111'
            ]);
        }
        
        $this->enableForeignKeys();
    }
}

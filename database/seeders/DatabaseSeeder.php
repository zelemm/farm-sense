<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        try {
            User::create([
                'name'       => 'Leighton',
                'lang'       => 'en',
                'user_type'  => 'super_admin',
                'role'  => 'super_admin',
                'email'      => 'help@maintainme.com.au',
                'password' => bcrypt('Shaddy@#19834'),
            ]);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}

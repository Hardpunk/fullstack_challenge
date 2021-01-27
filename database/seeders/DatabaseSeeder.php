<?php

namespace Database\Seeders;

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
        // php artisan migrate:fresh --seed
        // $this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
    }
}

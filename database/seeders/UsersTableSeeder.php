<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Create the initial user.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Usuário Admin',
            'email' => 'admin@teste.com',
            'password' => Hash::make('123456'),
        ]);
    }
}

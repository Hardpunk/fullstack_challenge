<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions[] = Permission::create(['name' => 'register users']);
        $permissions[] = Permission::create(['name' => 'create users']);
        $permissions[] = Permission::create(['name' => 'store users']);
        $permissions[] = Permission::create(['name' => 'edit users']);
        $permissions[] = Permission::create(['name' => 'update users']);
        $permissions[] = Permission::create(['name' => 'view users']);
        $permissions[] = Permission::create(['name' => 'delete users']);
        $permissions[] = Permission::create(['name' => 'edit user profile']);
        $permissions[] = Permission::create(['name' => 'edit user role']);
        $permissions[] = Permission::create(['name' => 'manage roles']);
        $permissions[] = Permission::create(['name' => 'manage permissions']);

        // create roles and assign existing permissions
        $roleAdmin = Role::create(['name' => 'admin']);
        foreach($permissions as $permission) {
            $roleAdmin->givePermissionTo($permission->name);
        }

        $roleClients = Role::create(['name' => 'client']);
        $roleClients->givePermissionTo('register users');
        $roleClients->givePermissionTo('view users');
        $roleClients->givePermissionTo('edit user profile');

        $userAdmin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@teste.com',
            'password' => Hash::make('123456'),
        ]);

        $userClient = User::create([
            'name' => 'Client User',
            'email' => 'client@teste.com',
            'password' => Hash::make('123456'),
        ]);

        $userAdmin->assignRole($roleAdmin);
        $userClient->assignRole($roleClients);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']); 
        Role::create(['name' => 'public']);

        $viewUsers = Permission::create(['name' => 'view users']);

        //Permission assigning to role
        $admin->givePermissionTo($viewUsers);
    }
}

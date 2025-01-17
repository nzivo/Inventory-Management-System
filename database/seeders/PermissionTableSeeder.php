<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-create',
            'permission-create',
            'permission-list',
            'permission-delete',
            'permission-edit',
            'users-view',
            'assets-view',
        ];

        // Loop through each permission and create it
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create the Super Admin role
        $superAdminRole = Role::create(['name' => 'Super Admin']);

        // Assign all permissions to the Super Admin role
        $superAdminRole->givePermissionTo($permissions);
    }
}

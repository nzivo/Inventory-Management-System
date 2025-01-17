<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the user with specific details
        $user = User::create([
            'name' => 'System Admin',
            'email' => 'systemadmin@host.com',
            'username' => 'systemadmin',
            'password' => Hash::make('password'), // Ensure the password is hashed
            'designation_id' => 1,  // Assuming 'Systems Admin' designation is the first entry
        ]);

        // Attach the Super Admin role to the user
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $user->assignRole($superAdminRole);
    }
}

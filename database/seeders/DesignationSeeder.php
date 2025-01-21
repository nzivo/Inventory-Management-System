<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default department with a designation
        DB::table('designations')->insert([
            'designation_name' => 'Systems Admin',
            'department_name' => 'Operations',
        ]);
    }
}

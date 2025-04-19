<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name' => 'Human Resources'],
            ['name' => 'Finance'],
            ['name' => 'Information Technology'],
            ['name' => 'Marketing'],
            ['name' => 'Operations'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create HRD user
        $hrd = User::create([
            'name' => 'Admin HRD',
            'email' => 'hrd@example.com',
            'password' => Hash::make('password'),
            'role' => 'hrd',
            'department_id' => Department::where('name', 'Human Resources')->first()->id,
        ]);

        // Create department heads
        $departments = Department::all();
        $departmentHeads = [];

        foreach ($departments as $department) {
            $head = User::create([
                'name' => 'Kepala ' . $department->name,
                'email' => 'kepala.' . strtolower(str_replace(' ', '', $department->name)) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'kepala_departemen',
                'department_id' => $department->id,
            ]);
            
            $departmentHeads[$department->id] = $head->id;
            
            // Update department with head_id
            $department->update(['head_id' => $head->id]);
        }

        // Create supervisors for each department
        $supervisors = [];
        foreach ($departments as $department) {
            $supervisor = User::create([
                'name' => 'Supervisor ' . $department->name,
                'email' => 'supervisor.' . strtolower(str_replace(' ', '', $department->name)) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'atasan',
                'department_id' => $department->id,
                'supervisor_id' => $departmentHeads[$department->id],
            ]);
            
            $supervisors[$department->id] = $supervisor->id;
        }

        // Create regular employees for each department
        foreach ($departments as $department) {
            for ($i = 1; $i <= 3; $i++) {
                User::create([
                    'name' => 'Pegawai ' . $i . ' ' . $department->name,
                    'email' => 'pegawai' . $i . '.' . strtolower(str_replace(' ', '', $department->name)) . '@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'pegawai',
                    'department_id' => $department->id,
                    'supervisor_id' => $supervisors[$department->id],
                ]);
            }
        }
    }
}
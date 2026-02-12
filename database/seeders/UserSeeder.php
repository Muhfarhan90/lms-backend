<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 1, // Assuming 1 is the ID for admin role
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Elon Musk',
                'email' => 'elon.musk@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 2, // Assuming 2 is the ID for instructor role
                'is_active' => true,
            ],
            [
                'name' => 'student',
                'email' => 'student@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => 3, // Assuming 3 is the ID for student role
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

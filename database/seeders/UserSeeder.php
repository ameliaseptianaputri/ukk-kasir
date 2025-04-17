<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('1234'),  // Admin harus login dengan password
                'role' => 'admin',  // Role admin
                'is_seeded' => true, // Tandai sebagai user dari seeder

            ],
            [
                'name' => 'petugas',
                'email' => 'petugas@gmail.com',
                'password' => Hash::make('1234'),  // Petugas harus login dengan password
                'role' => 'petugas',  // Role Petugas
                'is_seeded' => true, // Tandai sebagai user dari seeder

            ]
        ];
        foreach ($users as $user) {
            User::create($user); // Menyimpan data ke database
        }
    }
}
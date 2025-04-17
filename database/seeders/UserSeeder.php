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
                'password' => Hash::make('1234'), 
                'role' => 'admin',  
                'is_seeded' => true, 

            ],
            [
                'name' => 'petugas',
                'email' => 'petugas@gmail.com',
                'password' => Hash::make('1234'),  
                'role' => 'petugas',  
                'is_seeded' => true, 

            ]
        ];
        foreach ($users as $user) {
            User::create($user); 
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'Admin',
                'lname' => 'admin',
                'email' => 'admin3@go.buu.ac.th',
                'is_admin' => '1',
                'password' => bcrypt('adminisadmin'),
                'phonenumber' => '0123456789',
            ],
            [
                'name' => 'User',
                'lname' => 'user',
                'email' => 'user3@go.buu.ac.th',
                'is_admin' => '0',
                'password' => bcrypt('1234'),
                'phonenumber' => '0123456789',
            ]

        ];

        foreach($user as $key => $value) {
            User::create($value);
        }
    }
}

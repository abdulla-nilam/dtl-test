<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert(
            [
                [
                    'id' => 1, # hard coding id cz of first record
                    'name' => 'Tom',
                    'email' => 'tom@sirwiss.com',
                    'email_verified_at' => now(),
                    'password' => bcrypt('123'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2, # hard coding id cz of first record
                    'name' => 'Jerry',
                    'email' => 'jerry@sirwiss.com',
                    'email_verified_at' => now(),
                    'password' => bcrypt('123'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]
        );
    }
}

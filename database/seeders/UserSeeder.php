<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Office',
                'email' => 'office@gmail.com',
                'password' => bcrypt('1234'),
                'role' => 'office'
            ],
            [
                'name' => 'Laboratory',
                'email' => 'lab@gmail.com',
                'password' => bcrypt('1234'),
                'role' => 'laboratory'
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => bcrypt('1234'),
                'role' => 'user'
            ]
        ]);
    }
}

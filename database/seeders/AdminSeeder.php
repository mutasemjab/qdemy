<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
     public function run()
    {
        DB::table('admins')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin'),
            'is_super' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

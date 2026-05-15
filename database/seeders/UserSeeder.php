<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('users')->insert([
            [
                'name'=>'roni',
                'email'=>'dev.ronisingha@gmail.com',
                'phone' => '01660033965',
                'password' => Hash::make('12345678'),
                'role'=>'admin',
                'address'=>'Moulvibazar,Sylhet.BD'
            ],
            [
                'name'=>'toni',
                'email'=>'tonisingha@gmail.com',
                'phone' => '01660033999',
                'password' => Hash::make('12345678'),
                'role'=>'customer',
                'address'=>'Moulvibazar,Sylhet.BD'
            ],
       ]);
    }
}

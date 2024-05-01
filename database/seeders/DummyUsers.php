<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'nik' => '123456789',
                'name' => 'Jono',
                'email' => 'jono@gmail.com',
                'no_hp' => '08123456789',
                'password' => bcrypt('karyawan123'),
                'role' => 'Karyawan',
            ],
            [
                'nik' => '012345678',
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'no_hp' => '08098765432',
                'password' => bcrypt('admin123'),
                'role' => 'Admin',
            ]
        ];
        
        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}

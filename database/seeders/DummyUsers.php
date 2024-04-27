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
                'password' => bcrypt('crew123'),
                'role' => 'crew',
            ],
            [
                'nik' => '012345678',
                'name' => 'Dadang',
                'email' => 'dadang@gmail.com',
                'no_hp' => '08098765432',
                'password' => bcrypt('kepala123'),
                'role' => 'kepala toko',
            ],
            [
                'nik' => '987654321',
                'name' => 'Ahmad',
                'email' => 'ahmad@gmail.com',
                'no_hp' => '08987654321',
                'password' => bcrypt('asisten123'),
                'role' => 'asisten kepala toko',
            ],
        ];
        
        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}

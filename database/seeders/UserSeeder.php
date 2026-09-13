<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Account PIC / Admin Bappeda
        User::create([
            'name' => 'PIC BAPPEDA',
            'email' => 'admin@bappeda.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '081234567890',
        ]);

        // Dummy OPD 1
        User::create([
            'name' => 'Dinas Pendidikan',
            'email' => 'disdik@bappeda.go.id',
            'password' => Hash::make('password'),
            'role' => 'opd',
            'phone_number' => '088800001111',
        ]);

        // Dummy OPD 2
        User::create([
            'name' => 'Dinas Kesehatan',
            'email' => 'dinkes@bappeda.go.id',
            'password' => Hash::make('password'),
            'role' => 'opd',
            'phone_number' => '088800002222',
        ]);
    }
}
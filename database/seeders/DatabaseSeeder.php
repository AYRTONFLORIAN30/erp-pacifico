<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CARLOS (Dueño)
        User::create([
            'name' => 'Carlos Pariona',
            'email' => 'carlospariona@gmail.com',
            'password' => Hash::make('password'),
            'rol' => 'admin',
        ]);

        // 2. LUZBITH (Egresos)
        User::create([
            'name' => 'Luzbith',
            'email' => 'admin@pacifico.com',
            'password' => Hash::make('password'),
            'rol' => 'egresos',
        ]);

        // 3. SARA (Ventas)
        User::create([
            'name' => 'Sara',
            'email' => 'registroscaja@gmail.com',
            'password' => Hash::make('password'),
            'rol' => 'ventas',
        ]);

        // 4. ALMENDRA (Saldos)
        User::create([
            'name' => 'Almendra',
            'email' => 'saldos@gmail.com',
            'password' => Hash::make('password'),
            'rol' => 'saldos',
        ]);

        // 5. RUTH (Planilla)
        User::create([
            'name' => 'Ruth',
            'email' => 'planilla@gmail.com',
            'password' => Hash::make('password'),
            'rol' => 'planilla',
        ]);
    }
}
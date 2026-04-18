<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nome'     => 'Administrador',
            'email'    => 'admin@sced.com',
            'password' => Hash::make('admin123'),
            'perfil'   => 'administrador',
            'status'   => 'ativo',
        ]);
    }
}
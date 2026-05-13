<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamento; // Importante adicionar este
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criamos (ou buscamos) o departamento primeiro
        $depto = Departamento::firstOrCreate(['nome' => 'SUPORTE']);

        // 2. Criamos o usuário usando o ID do departamento criado acima
        User::create([
            'nome'            => 'Administrador',
            'email'           => 'admin@sced.com',
            'password'        => Hash::make('admin123'),
            'perfil'          => 'administrador',
            'status'          => 'ativo',
            'departamento_id' => $depto->id,
            'cargo'           => 'N3',
        ]);
    }
}
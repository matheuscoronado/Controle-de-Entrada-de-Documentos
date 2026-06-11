<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria (ou busca) o departamento
        $depto = Departamento::firstOrCreate(['nome' => 'SUPORTE']);

        // 2. CORREÇÃO: usa firstOrCreate para não quebrar em re-execuções do seeder
        //    (antes usava User::create, que lançava exception de e-mail duplicado)
        User::firstOrCreate(
            ['email' => 'admin@sced.com'],
            [
                'nome'            => 'Administrador',
                'password'        => Hash::make('admin123'),
                'perfil'          => 'administrador',
                'status'          => 'ativo',
                'departamento_id' => $depto->id,
                'cargo'           => 'N3',
            ]
        );
    }
}

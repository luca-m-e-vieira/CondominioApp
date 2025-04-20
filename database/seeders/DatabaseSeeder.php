<?php

namespace Database\Seeders;

use App\Models\Morador;
use App\Models\User;
use App\Models\Condominio;
use App\Models\Apartamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== ADMIN ==========
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@condominios.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        // ========== SÍNDICOS ==========
        $sindico1 = User::create([
            'name' => 'Síndico João',
            'email' => 'sindico1@condominios.com',
            'password' => Hash::make('12345678'),
            'role' => 'sindico'
        ]);

        $sindico2 = User::create([
            'name' => 'Síndica Maria',
            'email' => 'sindico2@condominios.com',
            'password' => Hash::make('12345678'),
            'role' => 'sindico'
        ]);

        // ========== CONDOMÍNIOS ==========
        $cond1 = Condominio::create([
            'nome' => 'Residencial Sol Nascente',
            'endereco' => 'Rua das Flores, 100'
        ]);

        $cond2 = Condominio::create([
            'nome' => 'Edifício Mar Atlântico',
            'endereco' => 'Avenida Beira Mar, 200'
        ]);

        // ========== VINCULAR SÍNDICOS AOS CONDOMÍNIOS ==========
        // Cond1: Síndico1 ativo, Síndico2 inativo
        $cond1->sindicos()->attach($sindico1, ['ativo' => true]);
        $cond1->sindicos()->attach($sindico2, ['ativo' => false]);

        // Cond2: Síndico2 ativo
        $cond2->sindicos()->attach($sindico2, ['ativo' => true]);

        // ========== MORADORES ==========
        $morador1 = Morador::create([
            'nome' => 'Carlos Silva',
            'condominio_id' => $cond1->id
        ]);
        
        // Adicione esta linha:
        $morador2 = Morador::create([
            'nome' => 'Ana Souza',
            'condominio_id' => $cond1->id
        ]);
        
        $moradorExpulso = Morador::create([
            'nome' => 'Morador Expulso',
            'condominio_id' => null
        ]);
        
        // Vincular apartamentos aos moradores:
        Apartamento::create([
            'numero' => '101',
            'condominio_id' => $cond1->id,
            'morador_id' => $morador1->id
        ]);

        // ========== APARTAMENTOS ==========
        // Apartamentos no cond1
        $apt1 = Apartamento::create([
            'numero' => '101',
            'condominio_id' => $cond1->id,
            'morador_id' => $morador1->id
        ]);

        $apt2 = Apartamento::create([
            'numero' => '102',
            'condominio_id' => $cond1->id,
            'morador_id' => $morador2->id
        ]);

        // Apartamento vago no cond1
        $apt3 = Apartamento::create([
            'numero' => '103',
            'condominio_id' => $cond1->id,
            'morador_id' => null
        ]);

        // Apartamento do morador expulso (deve estar vago)
        $aptExpulso = Apartamento::create([
            'numero' => '201',
            'condominio_id' => $cond2->id,
            'morador_id' => null
        ]);
    }
}
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
        $this->createAdministrativeUsers();
        $this->createCondominiosWithSpecificScenarios();
        $this->createUnattachedEntities();
    }

    private function createAdministrativeUsers(): void
    {
        // Admin principal
        User::create([
            'name' => 'Marcos Silva',
            'email' => 'admin@condominios.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);
    }

    private function createCondominiosWithSpecificScenarios(): void
    {
        // Criar síndicos primeiro
        $sindicos = [
            User::create([
                'name' => 'Fernanda Oliveira',
                'email' => 'sindico1@condominios.com',
                'password' => Hash::make('12345678'),
                'role' => 'sindico'
            ]),
            User::create([
                'name' => 'Ricardo Santos',
                'email' => 'sindico2@condominios.com',
                'password' => Hash::make('12345678'),
                'role' => 'sindico'
            ]),
            User::create([
                'name' => 'Patrícia Costa',
                'email' => 'sindico3@condominios.com',
                'password' => Hash::make('12345678'),
                'role' => 'sindico'
            ]),
            User::create([
                'name' => 'Carlos Mendes',
                'email' => 'sindico4@condominios.com',
                'password' => Hash::make('12345678'),
                'role' => 'sindico'
            ])
        ];

        // 1. Condomínio com 4 apartamentos:
        // - 1 vago
        // - 2 com o mesmo morador
        // - 1 com outro morador
        // - com síndico
        $cond1 = Condominio::create([
            'nome' => 'Residencial Jardins',
            'endereco' => 'Avenida das Palmeiras, 1500'
        ]);
        $cond1->sindicos()->attach($sindicos[0], ['ativo' => true]);

        $morador1_1 = Morador::create(['nome' => 'João Silva', 'condominio_id' => $cond1->id]);
        $morador1_2 = Morador::create(['nome' => 'Maria Oliveira', 'condominio_id' => $cond1->id]);

        Apartamento::create(['numero' => '101', 'condominio_id' => $cond1->id, 'morador_id' => $morador1_1->id]);
        Apartamento::create(['numero' => '102', 'condominio_id' => $cond1->id, 'morador_id' => $morador1_1->id]);
        Apartamento::create(['numero' => '103', 'condominio_id' => $cond1->id, 'morador_id' => $morador1_2->id]);
        Apartamento::create(['numero' => '104', 'condominio_id' => $cond1->id]);

        // 2. Condomínio com 5 apartamentos:
        // - 3 vagos
        // - 2 cada um com um morador diferente
        // - com síndico
        $cond2 = Condominio::create([
            'nome' => 'Edifício Golden Tower',
            'endereco' => 'Rua Oscar Freire, 800'
        ]);
        $cond2->sindicos()->attach($sindicos[1], ['ativo' => true]);

        $morador2_1 = Morador::create(['nome' => 'Pedro Almeida', 'condominio_id' => $cond2->id]);
        $morador2_2 = Morador::create(['nome' => 'Ana Paula', 'condominio_id' => $cond2->id]);

        Apartamento::create(['numero' => '201', 'condominio_id' => $cond2->id, 'morador_id' => $morador2_1->id]);
        Apartamento::create(['numero' => '202', 'condominio_id' => $cond2->id, 'morador_id' => $morador2_2->id]);
        Apartamento::create(['numero' => '203', 'condominio_id' => $cond2->id]);
        Apartamento::create(['numero' => '204', 'condominio_id' => $cond2->id]);
        Apartamento::create(['numero' => '205', 'condominio_id' => $cond2->id]);

        // 3. Condomínio sem apartamentos e com 2 moradores
        // - com síndico
        $cond3 = Condominio::create([
            'nome' => 'Condomínio Parque Verde',
            'endereco' => 'Rua das Acácias, 350'
        ]);
        $cond3->sindicos()->attach($sindicos[2], ['ativo' => true]);

        Morador::create(['nome' => 'Luiz Fernando', 'condominio_id' => $cond3->id]);
        Morador::create(['nome' => 'Carla Santos', 'condominio_id' => $cond3->id]);

        // 4. Condomínio com 3 apartamentos sem moradores
        // - com síndico
        $cond4 = Condominio::create([
            'nome' => 'Residencial Bella Vista',
            'endereco' => 'Alameda Santos, 2000'
        ]);
        $cond4->sindicos()->attach($sindicos[3], ['ativo' => true]);

        Apartamento::create(['numero' => '301', 'condominio_id' => $cond4->id]);
        Apartamento::create(['numero' => '302', 'condominio_id' => $cond4->id]);
        Apartamento::create(['numero' => '303', 'condominio_id' => $cond4->id]);

        // 5. Condomínio sem nada
        Condominio::create([
            'nome' => 'Condomínio Vista Linda',
            'endereco' => 'Alameda Campinas, 750'
        ]);
    }

    private function createUnattachedEntities(): void
    {
        // Síndico sem condomínio
        User::create([
            'name' => 'Roberto Nunes',
            'email' => 'roberto@condominios.com',
            'password' => Hash::make('12345678'),
            'role' => 'sindico'
        ]);

        // 3 moradores sem condomínio ou apartamento
        Morador::create(['nome' => 'Felipe Costa']);
        Morador::create(['nome' => 'Juliana Pereira']);
        Morador::create(['nome' => 'Rafael Mendes']);
    }
}
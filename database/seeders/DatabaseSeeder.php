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
        $this->createAllCondominiumCombinations();
        $this->createSpecialCases();
    }

    private function createAdministrativeUsers(): void
    {
        
        User::create([
            'name' => 'Admin Master',
            'email' => 'admin@condominios.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        
        $sindicos = [
            ['name' => 'Síndico João', 'email' => 'sindico1@condominios.com'],
            ['name' => 'Síndica Maria', 'email' => 'sindico2@condominios.com'],
            ['name' => 'Síndico Carlos', 'email' => 'sindico3@condominios.com']
        ];

        foreach ($sindicos as $sindico) {
            User::create([
                'name' => $sindico['name'],
                'email' => $sindico['email'],
                'password' => Hash::make('12345678'),
                'role' => 'sindico'
            ]);
        }
    }

    private function createAllCondominiumCombinations(): void
    {
        
        $sindicos = User::where('role', 'sindico')->get();
        $sindicoIndex = 0;

        
        $condominiosComSindico = [
            [
                'nome' => 'Residencial Sol Nascente',
                'endereco' => 'Rua das Flores, 100',
                'com_apartamentos' => true,
                'com_moradores' => true
            ],
            [
                'nome' => 'Edifício Sem Apartamentos',
                'endereco' => 'Avenida Principal, 200',
                'com_apartamentos' => false,
                'com_moradores' => true
            ],
            [
                'nome' => 'Residencial Sem Moradores',
                'endereco' => 'Rua Secundária, 300',
                'com_apartamentos' => true,
                'com_moradores' => false
            ]
        ];

        foreach ($condominiosComSindico as $condominioData) {
            
            $cond = Condominio::create([
                'nome' => $condominioData['nome'],
                'endereco' => $condominioData['endereco']
            ]);

            
            if ($sindicoIndex < count($sindicos)) {
                $cond->sindicos()->attach($sindicos[$sindicoIndex], ['ativo' => true]);
                $sindicoIndex++;
            }

            
            if ($condominioData['com_moradores']) {
                $morador = Morador::create(['nome' => 'Morador do ' . $condominioData['nome'], 'condominio_id' => $cond->id]);
                
                
                if ($condominioData['com_apartamentos']) {
                    Apartamento::create([
                        'numero' => '101',
                        'condominio_id' => $cond->id,
                        'morador_id' => $morador->id
                    ]);
                    Apartamento::create([
                        'numero' => '102',
                        'condominio_id' => $cond->id
                    ]);
                }
            } elseif ($condominioData['com_apartamentos']) {
                Apartamento::create(['numero' => '201', 'condominio_id' => $cond->id]);
                Apartamento::create(['numero' => '202', 'condominio_id' => $cond->id]);
            }
        }

        
        $condominiosSemSindico = [
            [
                'nome' => 'Condomínio Sem Síndico',
                'endereco' => 'Travessa da Paz, 400',
                'com_apartamentos' => true,
                'com_moradores' => true
            ],
            [
                'nome' => 'Condomínio Vazio',
                'endereco' => 'Alameda dos Sonhos, 500',
                'com_apartamentos' => false,
                'com_moradores' => false
            ],
            [
                'nome' => 'Edifício Sem Síndico e Moradores',
                'endereco' => 'Avenida Central, 600',
                'com_apartamentos' => true,
                'com_moradores' => false
            ],
            [
                'nome' => 'Residencial Sem Síndico e Apartamentos',
                'endereco' => 'Rua das Árvores, 700',
                'com_apartamentos' => false,
                'com_moradores' => true
            ],
            [
                'nome' => 'Condomínio Sem Moradores e Apartamentos',
                'endereco' => 'Avenida das Flores, 800',
                'com_apartamentos' => false,
                'com_moradores' => false
            ]
        ];

        foreach ($condominiosSemSindico as $condominioData) {
            $cond = Condominio::create([
                'nome' => $condominioData['nome'],
                'endereco' => $condominioData['endereco']
            ]);

           
            if ($condominioData['com_moradores']) {
                $morador = Morador::create(['nome' => 'Morador do ' . $condominioData['nome'], 'condominio_id' => $cond->id]);
                
                
                if ($condominioData['com_apartamentos']) {
                    Apartamento::create([
                        'numero' => '101',
                        'condominio_id' => $cond->id,
                        'morador_id' => $morador->id
                    ]);
                }
            } elseif ($condominioData['com_apartamentos']) {
                Apartamento::create(['numero' => '201', 'condominio_id' => $cond->id]);
                Apartamento::create(['numero' => '202', 'condominio_id' => $cond->id]);
            }
        }
    }

    private function createSpecialCases(): void
    {
        
        Morador::create(['nome' => 'Morador Expulso']);
        
        
        Morador::create(['nome' => 'Morador Sem Condomínio']);
    }
}
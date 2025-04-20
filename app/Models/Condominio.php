<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Condominio extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'endereco',
    ];
    // Relação com apartamentos
    public function apartamentos(): HasMany
    {
        return $this->hasMany(Apartamento::class);
    }

    // Relação com síndicos (usuários)
    public function sindicos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sindico_condominio')
            ->withPivot('ativo')
            ->withTimestamps();
    }

    // Síndico ativo
    public function sindicoAtivo()
    {
        return $this->sindicos()->wherePivot('ativo', true)->first();
    }

    // Moradores através dos apartamentos
    public function moradores(): HasManyThrough
    {
        return $this->hasManyThrough(
            Morador::class,
            Apartamento::class,
            'condominio_id',    // FK na tabela apartamentos
            'id',               // FK na tabela moradores (morador_id)
            'id',               // PK na tabela condominios
            'morador_id'        // FK na tabela apartamentos
        )->distinct();
    }

    // Moradores sem apartamento (se existir relação direta)
    public function moradoresDiretos()
    {
        // Relação direta com moradores (se existir condominio_id na tabela moradores)
        return $this->hasMany(Morador::class, 'condominio_id');
    }
    
    // Método para obter todos os moradores (com ou sem apartamento)
    public function getTodosMoradoresAttribute()
    {
        // Moradores com apartamento (através da relação hasManyThrough)
        $moradoresComApto = $this->moradores;
        
        // Moradores sem apartamento (relação direta)
        $moradoresSemApto = $this->moradoresDiretos()
            ->whereDoesntHave('apartamentos')
            ->get();
        
        // Combine as coleções
        return $moradoresComApto->merge($moradoresSemApto);
    }
}
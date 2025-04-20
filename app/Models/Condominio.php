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

   
    public function sindicos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sindico_condominio')
            ->withPivot('ativo')
            ->withTimestamps();
    }

  
    public function sindicoAtivo()
    {
        return $this->sindicos()->wherePivot('ativo', true)->first();
    }

    public function moradores(): HasManyThrough
    {
        return $this->hasManyThrough(
            Morador::class,
            Apartamento::class,
            'condominio_id',    
            'id',              
            'id',               
            'morador_id'        
        )->distinct();
    }

    public function moradoresDiretos()
    {
        // Relação direta com moradores (se existir condominio_id na tabela moradores)
        return $this->hasMany(Morador::class, 'condominio_id');
    }
    

    public function getTodosMoradoresAttribute()
    {
        
        $moradoresComApto = $this->moradores;
        
        // Moradores sem apartamento (relação direta)
        $moradoresSemApto = $this->moradoresDiretos()
            ->whereDoesntHave('apartamentos')
            ->get();
        
        // Combine as coleções
        return $moradoresComApto->merge($moradoresSemApto);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Morador extends Model
{
    use HasFactory;
    
    protected $table = 'moradores';
    
    protected $fillable = [
        'nome',
        'email',
        'condominio_id',
        'expulso'
    ];

    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class);
    }

    public function apartamentos(): HasMany
    {
        return $this->hasMany(Apartamento::class, 'morador_id');
    }

}

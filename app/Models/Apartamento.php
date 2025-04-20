<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apartamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'condominio_id',
        'morador_id'
    ];
    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class);
    }

    public function morador(): BelongsTo
    {
        return $this->belongsTo(Morador::class, 'morador_id'); // Referência à model Morador
    }
}

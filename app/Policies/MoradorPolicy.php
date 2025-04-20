<?php

namespace App\Policies;

use App\Models\Morador;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MoradorPolicy
{
        /**
         * Determine whether the user can view any models.
         */
    public function viewAny(User $user)
    {
        return $user->role === 'admin' || $user->role === 'sindico';
    }

    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role === 'sindico';
    }

    public function update(User $user, Morador $morador)
    {
        if ($user->role === 'admin') return true;
    
        if ($user->role === 'sindico') {
            $condominioAtivo = $user->condominios()->wherePivot('ativo', true)->first();
            return $condominioAtivo && $morador->condominio_id === $condominioAtivo->id;
        }
    
        return false;
    }

    public function delete(User $user, Morador $morador)
    {
        return $this->update($user, $morador);
    }
}

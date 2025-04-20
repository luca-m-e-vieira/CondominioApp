<?php

namespace App\Policies;

use App\Models\Apartamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApartamentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'sindico';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Apartamento $apartamento): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'sindico';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Apartamento $apartamento)
    {
        if ($user->role === 'admin') return true;
        
        return $user->role === 'sindico' && 
               $apartamento->condominio->sindicos->contains($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */

     public function delete(User $user, Apartamento $apartamento)
     {
         return $this->update($user, $apartamento);
     }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Apartamento $apartamento): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Apartamento $apartamento): bool
    {
        //
    }
}

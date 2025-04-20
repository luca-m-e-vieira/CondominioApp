<?php

namespace App\Policies;

use App\Models\Condominio;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CondominioPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin'; // Apenas admin pode listar todos condomínios
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Condominio $condominio)
    {
        return $user->role === 'admin' || $condominio->sindicos->contains($user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }
    
    public function update(User $user, Condominio $condominio): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Condominio $condominio): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Condominio $condominio): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Condominio $condominio): bool
    {
        //
    }
}

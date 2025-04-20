<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
// app/Policies/UserPolicy.php

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin'; // Só admin pode listar usuários
    }
    
    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin'; // Só admin pode ver detalhes
    }
    
    public function create(User $user): bool
    {
        return $user->role === 'admin'; // Só admin pode criar usuários
    }
    
    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin'; // Só admin pode editar
    }
    
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin' && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'admin'; 
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin'; 
    }
    
    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin'; 
    }
    
    public function create(User $user): bool
    {
        return $user->role === 'admin'; 
    }
    
    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin'; 
    }
    
    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin' && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */


    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'admin'; 
    }
}

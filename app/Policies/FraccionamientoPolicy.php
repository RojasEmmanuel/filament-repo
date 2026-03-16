<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Fraccionamiento;
use Illuminate\Auth\Access\HandlesAuthorization;

class FraccionamientoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Fraccionamiento');
    }

    public function view(AuthUser $authUser, Fraccionamiento $fraccionamiento): bool
    {
        return $authUser->can('View:Fraccionamiento');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Fraccionamiento');
    }

    public function update(AuthUser $authUser, Fraccionamiento $fraccionamiento): bool
    {
        return $authUser->can('Update:Fraccionamiento');
    }

    public function delete(AuthUser $authUser, Fraccionamiento $fraccionamiento): bool
    {
        return $authUser->can('Delete:Fraccionamiento');
    }

    public function restore(AuthUser $authUser, Fraccionamiento $fraccionamiento): bool
    {
        return $authUser->can('Restore:Fraccionamiento');
    }

    public function forceDelete(AuthUser $authUser, Fraccionamiento $fraccionamiento): bool
    {
        return $authUser->can('ForceDelete:Fraccionamiento');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Fraccionamiento');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Fraccionamiento');
    }

    public function replicate(AuthUser $authUser, Fraccionamiento $fraccionamiento): bool
    {
        return $authUser->can('Replicate:Fraccionamiento');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Fraccionamiento');
    }

}
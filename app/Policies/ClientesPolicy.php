<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Clientes;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Clientes');
    }

    public function view(AuthUser $authUser, Clientes $clientes): bool
    {
        return $authUser->can('View:Clientes');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Clientes');
    }

    public function update(AuthUser $authUser, Clientes $clientes): bool
    {
        return $authUser->can('Update:Clientes');
    }

    public function delete(AuthUser $authUser, Clientes $clientes): bool
    {
        return $authUser->can('Delete:Clientes');
    }

    public function restore(AuthUser $authUser, Clientes $clientes): bool
    {
        return $authUser->can('Restore:Clientes');
    }

    public function forceDelete(AuthUser $authUser, Clientes $clientes): bool
    {
        return $authUser->can('ForceDelete:Clientes');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Clientes');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Clientes');
    }

    public function replicate(AuthUser $authUser, Clientes $clientes): bool
    {
        return $authUser->can('Replicate:Clientes');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Clientes');
    }

}
<?php

// File: app/Policies/ResourcePolicy.php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

/**
 * ResourcePolicy
 * 
 * Håndterer autorisasjon for Resource-modellen.
 * Sikrer at brukere kun kan aksessere ressurser som tilhører deres tenant.
 */
class ResourcePolicy
{
    /**
     * Determine if the user can view the resource.
     * 
     * Sjekker at ressursen tilhører samme tenant som brukeren.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return bool
     */
    public function view(User $user, Resource $resource): bool
    {
        return $resource->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can update the resource.
     * 
     * Sjekker at ressursen tilhører samme tenant som brukeren.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return bool
     */
    public function update(User $user, Resource $resource): bool
    {
        return $resource->tenant_id === $user->tenant_id;
    }

    /**
     * Determine if the user can delete the resource.
     * 
     * Sjekker at ressursen tilhører samme tenant som brukeren.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Resource  $resource
     * @return bool
     */
    public function delete(User $user, Resource $resource): bool
    {
        return $resource->tenant_id === $user->tenant_id;
    }
}

// ResourcePolicy sikrer tenant-isolasjon ved å verifisere at brukere kun kan
// se, oppdatere og slette ressurser som tilhører deres egen tenant.

<?php

namespace App\Policies;

use App\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    
    /**
     * To Detrmaine User Can Update Project or Not
     * @param $project
     */

    public function update(User $user, Project $project)
    {
        return $user->is($project->owner) | $project->members->contains($user);
    }

    public function destroy(User $user, Project $project)
    {
        return $user->is($project->owner);
    }
}

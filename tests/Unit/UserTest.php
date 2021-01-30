<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_has_projects()
    {
        $user = factory(User::class)->create();
                
        ProjectSetup::belongsTo($user)->create();

        $this->assertInstanceOf(Project::class, $user->projects->first());
    }

    /** @test */
    public function a_use_has_accessible_projects()
    {
        // $user->projects + invitedProjects  ...
    }
}

<?php

namespace Tests\Unit;

use App\Project;
use App\User;
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
        factory(Project::class)->create(['owner_id'=>$user->id]);
        $this->assertInstanceOf(Collection::class, $user->projects);
    }
}

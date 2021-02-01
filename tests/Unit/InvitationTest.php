<?php
namespace Tests\Unit;

use App\Project;
use App\ProjectActivity;
use App\User;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function project_can_invite_user_and_has_members()
    {
        $this->withoutExceptionHandling();
        
        $project = ProjectSetup::create();

        $naghee = factory(User::class)->create();
        $taghee = factory(User::class)->create();

        $project->invite($naghee);
        $project->invite($taghee);

        $this->assertCount(2, $project->fresh()->members);
        $this->assertTrue($project->members->contains($taghee));
    }
}

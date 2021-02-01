<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        $project = ProjectSetup::create();
        $this->signIn($project->owner);
        $user = factory(User::class)->create();


        $this->post($project->path().'/invitation', [
            'email' => $user->email,
        ])->assertRedirect($project->path());

        $this->assertTrue($project->fresh()->members->contains($user));
    }
    /** @test */
    function inviting_user_email_must_be_an_site_member_account()
    {

    }

    /** @test  */
    function not_project_owner_users_cant_invite_user()
    {
        // invited user can not invite another user to  !!
    }
    
    /** @test */
    public function invited_user_can_edit_deltails_of_project()
    {
        $project = ProjectSetup::create();
        $taghe = factory(User::class)->create();
        $project->invite($taghe);
        $this->signIn($taghe);
        $this->patch($project->path(),['title' => 'Changed', 'description' => $project->description])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', ['title' => 'Changed']);

    }

    /** @test */
    public function user_can_see_projects_they_have_been_invited_to_in_dashboard()
    {
        
    }
}

<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_project_can_invite_a_user()
    {
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
        $project = ProjectSetup::create();
        $fakeEmail = $this->faker()->email;

        $this->signIn($project->owner);

        $this->post($project->path().'/invitation', [
            'email' => $fakeEmail,
        ])->assertSessionHasErrors('email');
    }

    /** @test  */
    function not_project_owner_users_cant_invite_user()
    {
        $project = ProjectSetup::create();
        $user = $this->signIn();

        // signed in user can not invite user
        $this->post($project->path().'/invitation', [
            'email' => $user->email,
        ])->assertForbidden();

        // invited user can not invite another one
        $project->invite($user);

        $this->post($project->path().'/invitation', [
            'email' => $user->email,
        ])->assertForbidden();
    }
    
    /** @test */
    public function invited_user_can_not_edit_entire_project()
    {
        $project = ProjectSetup::create();
        $taghe = $this->signIn();

        $project->invite($taghe);
        $this->get($project->path().'/edit')->assertForbidden();
        $this->patch($project->path(), ProjectSetup::raw())->assertForbidden();

    }

    /** @test */
    public function user_can_see_projects_they_have_been_invited_to_in_dashboard()
    {
        $project = ProjectSetup::create();
        $taghe = $this->signIn();

        $project->invite($taghe);
        $this->get('/projects')
            ->assertSee($project->title);

        $this->get($project->path())
            ->assertOk();
    }
}

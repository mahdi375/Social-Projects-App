<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();
        $attributes = [
            'title' => $this->faker()->sentence(4),
            'description' => $this->faker()->paragraph(3)
        ];

        $this->get("/projects/create")->assertOk();

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function guests_can_not_manage_project()
    {
        $project = factory(Project::class)->create();

        //cant see create page
        $this->get('/projects/create')->assertRedirect('/login');

        //cant create
        $this->post('/projects', $project->toArray())->assertRedirect('/login');

        //cant see one (show)
        $this->get($project->path())->assertRedirect('/login');

        //cant see all (index)
        $this->get('/projects')->assertRedirect('/login');
    }

    /** @test */
    public function a_title_is_required_to_create_a_project()
    {
        $this->signIn();
        $attributes = factory(Project::class)->raw(['title' => null]);

        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    }
    
    /** @test */
    public function a_description_is_required_to_create_a_project()
    {
        $this->signIn();
        $attributes = factory(Project::class)->raw(['description' => null]);

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);  
    }

    /** @test */
    public function a_user_can_only_see_their_project()
    {
        
        $user = $this->signIn();

        $theirProj = factory(Project::class)->create(['owner_id'=>$user->id]);
        $otherProj = factory(Project::class)->create();

        //can see their project
        $this->get($theirProj->path())
            ->assertOk()
            ->assertSee($theirProj->title);

        //can see only their projects
        $this->get('/projects')
            ->assertSee($theirProj->title)
            ->assertDontSee($otherProj->title);

        //can not see project of other user
        $this->get($otherProj->path())
            ->assertForbidden();
    }

}

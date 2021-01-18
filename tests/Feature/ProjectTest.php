<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->actingAs(factory(User::class)->create());
        
        $attributes = [
            'title' => $this->faker()->sentence(4),
            'description' => $this->faker()->paragraph(3)
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_title_is_required_to_create_a_project()
    {
        $this->actingAs(factory(User::class)->create());
        $attributes = factory(Project::class)->raw(['title' => null]);

        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    }
    
    /** @test */
    public function a_description_is_required_to_create_a_project()
    {
        $this->actingAs(factory(User::class)->create());
        $attributes = factory(Project::class)->raw(['description' => null]);

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);  
    }
    
    /** @test */
    public function guests_can_not_create_project()
    {
        $attributes = factory(Project::class)->raw();
        $this->post('/projects', $attributes)->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        $project = factory(Project::class)->create();

        $this->get("/projects/{$project->id}")->assertRedirect('/login');

        $this->actingAs(factory(User::class)->create());

        $this->get("/projects/{$project->id}")
            ->assertSee($project->title)
            ->assertSee($project->description);
    }
}

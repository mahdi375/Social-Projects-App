<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /** @test */
    public function a_project_has_path()
    {
        $project = ProjectSetup::create();

        $this->assertEquals("/projects/{$project->id}", $project->path());
    }

    /** @test */
    public function a_project_has_notes()
    {
        $attributes = ['notes' => $this->faker()->sentence()];
        $project = ProjectSetup::create($attributes);

        $this->assertDatabaseHas('projects', $project->toArray());
        $this->assertEquals($attributes['notes'], $project->notes);
    }

    /** @test */
    public function a_project_belongsTo_an_owner()
    {
        $project = ProjectSetup::create();

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = ProjectSetup::create();
        $task = ['body' => $this->faker()->sentence()];
        $task = $project->addTask($task);
        $this->assertTrue($project->tasks->contains($task));
    }
}

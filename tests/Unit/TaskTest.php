<?php

namespace Tests\Unit;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create();
        $task = ['body' => $this->faker()->sentence()];
        $task = $project->addTask($task);

        $this->assertInstanceOf(Project::class, $task->project);

        
    }

    /** @test */
    public function it_has_a_path()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create();
        $task = ['body' => $this->faker()->sentence()];
        $task = $project->addTask($task);

        $this->assertEquals("{$project->path()}/tasks/{$project->id}", $task->path());
    }
}

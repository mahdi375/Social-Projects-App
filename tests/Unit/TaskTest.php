<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $project = ProjectSetup::withTask(1)
            ->create();

        $this->assertInstanceOf(Project::class, $project->tasks[0]->project);        
    }

    /** @test */
    public function it_has_a_path()
    {
        $project = ProjectSetup::withTask(1)
            ->create();

        $this->assertEquals("{$project->path()}/tasks/{$project->tasks[0]->id}", $project->tasks[0]->path());
    }

    /** @test */
    public function it_can_checked_and_unchecked()
    {
        $task = factory(Task::class)->create();

        $this->assertFalse($task->wasChecked());

        $task->check();

        $this->assertTrue($task->wasChecked());
        
        $task->uncheck();
        
        $this->assertFalse($task->wasChecked());
    }
}

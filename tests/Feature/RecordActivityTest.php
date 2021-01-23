<?php

namespace Tests\Feature;

use App\ProjectActivity;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_project()
    {
        ProjectSetup::create();

        $this->assertCount(1, ProjectActivity::all());
        $this->assertEquals('created', ProjectActivity::all()->last()->description);
    }
    
    /** @test */
    public function updating_project()
    {
        $project = ProjectSetup::create();

        $data = ProjectSetup::raw();
        $this->signIn($project->owner);
        //update project as whole
        $this->patch($project->path(), $data);
        //update just 'notes'
        $this->patch($project->path().'/notes', ['notes' => 'changed']);

        $this->assertCount(3, ProjectActivity::all());
        $this->assertEquals('updated', ProjectActivity::all()->last()->description);
    }

    /** @test */
    public function creating_new_task()
    {
        ProjectSetup::withTask(1)->create();
        
        $activities = ProjectActivity::all();

        $this->assertCount(2, $activities);
        $this->assertEquals('task-added', $activities->last()->description);
    }
    
    /** @test */
    public function checking_task()
    {
        $project = ProjectSetup::withTask(1)->create();
        $task = $project->tasks[0];
        $this->signIn($project->owner);

        $data = [
            'body' => $task->body,
            'checked' => 'checked'
        ];

        $this->patch($task->path(), $data);

        $activities = ProjectActivity::all();

        $this->assertCount(3, $activities);
        $this->assertEquals('task-checked', $activities->last()->description);
        
    }
    
    /** @test */
    public function unchecking_task()
    {
        $project = ProjectSetup::withTask(1)->create();
        $task = $project->tasks[0];
        $this->signIn($project->owner);

        $data = [
            'body' => $task->body,
        ];

        $this->patch($task->path(), $data);

        $activities = ProjectActivity::all();

        $this->assertEquals('task-unchecked', $activities->last()->description);
    }
    
    /** @test */
    public function deleting_task()
    {
        $project = ProjectSetup::withTask()->create();

        $this->actingAs($project->owner)
            ->delete($project->tasks[0]->path());

        $activities = ProjectActivity::all();

        $this->assertCount(3, $activities);
        $this->assertEquals('task-deleted', $activities->last()->description);
    }
}

<?php

namespace Tests\Feature;

use App\Project;
use App\ProjectActivity;
use App\Task;
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

        $activity = ProjectActivity::all()->last();
        $this->assertCount(1, ProjectActivity::all());
        $this->assertEquals('project-created', $activity->description);
        $this->assertInstanceOf(Project::class, $activity->subject);
    }
    
    /** @test */
    public function updating_project()
    {
        $project = ProjectSetup::create();

        $data = ProjectSetup::raw();
        $this->signIn($project->owner);
        

        //update project as whole
        $this->patch($project->path(), $data);

        $expected = [
            'before' => [
                'title' => $project->title,
                'description' => $project->description
            ],
            'after' => [
                'title' => $data['title'],
                'description' => $data['description']
            ],
        ];
        $activity =  ProjectActivity::all()->last();

        $this->assertEquals($expected,$activity->changes);


        //update just 'notes'
        $this->patch($project->path().'/notes', ['notes' => 'changed']);

        $this->assertCount(3, ProjectActivity::all());
        $this->assertEquals('project-updated', ProjectActivity::all()->last()->description);
    }

    /** @test */
    public function creating_new_task()
    {
        $project = ProjectSetup::withTask(2)->create();
        $this->signIn($project->owner);        
        $activities = ProjectActivity::all();

        $this->assertCount(3, $activities);
        $this->assertEquals('task-created', $activities->last()->description);
        
        // a bug in RecordActivity trait
        $this->assertTrue($activities->last()->project->is($project));
        $this->assertInstanceOf(Task::class, $activities->last()->subject);
        $this->get($project->path())->assertSee('new task added');
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
        $this->assertInstanceOf(Task::class, $activities->last()->subject);
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
        $this->assertInstanceOf(Task::class, $activities->last()->subject);
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

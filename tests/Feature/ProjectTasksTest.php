<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Facades\Tests\Setup\ProjectSetup;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    /** @test */
    public function user_can_add_task_to_project()
    {
        //Given
        $user = $this->signIn();
        $project = ProjectSetup::belongsTo($user)->create();

        //When
        $task = ProjectSetup::rawTask();
        $response = $this->post($project->path().'/tasks', $task);

        //Then
        $response->assertRedirect($project->path());
        $this->get($project->path())->assertSee($task['body']);
        $this->assertEquals($task['body'], $project->fresh()->tasks()->first()->body);
    }

    /** @test */
    public function a_gust_can_not_manage_task()
    {
        $project = ProjectSetup::withTask()->create();
        $task = ProjectSetup::rawTask();

        // request for adding new test
        $this->post($project->path().'/tasks', $task)->assertRedirect('/login');
        // request for deleting existed task
        $this->delete($project->tasks[0]->path())->assertRedirect('/login');
    }

    /** @test */
    public function only_owner_of_project_can_add_tasks()
    {
        $taghee = factory(User::class)->create();

        $project = ProjectSetup::create();

        $task = ProjectSetup::rawTask();

        $this->actingAs($taghee)
            ->post($project->path().'/tasks', $task)
            ->assertForbidden();
        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function a_task_required_a_body()
    {
        $project = ProjectSetup::create();

        $task = ProjectSetup::rawTask(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', $task)
            ->assertSessionHasErrors(['body']);    
    }

    /** @test */
    public function a_task_can_be_updated_and_body_is_required()
    {
        //break it to three test (update_body, completed (checked) , body_required )
        $project = ProjectSetup::withTask(1, ['body' => 'old body'])
            ->create();

        $this->signIn($project->owner);

        $task = $project->tasks->first();

        $this->patch($task->path(), [
            'body' => '',
            'checked' => 'checked'
            ])->assertSessionHasErrors(['body']);

        $response = $this->patch($task->path(), [
            'body' => 'new body',
            'checked' => 'checked'
        ]);

        $task->refresh();
        
        $this->assertEquals('new body', $task->body);
        $this->assertTrue($task->wasChecked());
    }

    /** @test */
    public function a_task_can_marked_as_incomplete()
    {
        $project = ProjectSetup::withTask(1)->create();
        $task = $project->tasks[0];
        $this->signIn($project->owner);
        $task->check();
        $this->assertTrue($task->wasChecked());

        $data['body'] = $task->body;
        $this->patch($task->path(), $data);

        $this->assertFalse($task->fresh()->wasChecked());
    }
    /** @test */
    public function only_owner_of_project_can_update_tasks()
    {
        $project = ProjectSetup::withTask(1, ['body' => 'old body'])
            ->create();

        $this->signIn();

        $this->patch($project->tasks[0]->path(), [
            'body' => 'new body',
            'checked' => 'checked'
        ])->assertForbidden();
        $this->assertFalse($project->tasks[0]->fresh()->wasChecked());   
    }

    /** @test */
    public function project_owner_can_delete_task()
    {
        $project = ProjectSetup::withTask()->create();
        $task = $project->tasks[0];

        $this->actingAs($this->signIn())
            ->delete($task->path())
            ->assertForbidden();
        
        $this->actingAs($project->owner)
            ->delete($task->path())
            ->assertRedirect($project->path());
        
        $this->assertDatabaseMissing('tasks', $task->toArray());
    }
}

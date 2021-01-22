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
    public function a_gust_can_not_add_task()
    {
        $project = ProjectSetup::create();
        $task = ['body' => $this->faker()->sentence()];

        $this->post($project->path().'/tasks', $task)->assertRedirect('/login');
    }

    /** @test */
    public function only_owner_of_project_can_add_tasks()
    {
        $aUser = factory(User::class)->create();

        $project = ProjectSetup::create();

        $task = ['body' => $this->faker()->sentence()];

        $this->actingAs($aUser)
            ->post($project->path().'/tasks', $task)
            ->assertForbidden();
        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function a_project_can_has_task()
    {
        //Given
        $user = $this->signIn();
        $project = ProjectSetup::belongsTo($user)->create();

        //When
        $task = ['body' => $this->faker()->sentence()];
        $response = $this->post($project->path().'/tasks', $task);

        //Then
        $response->assertRedirect($project->path());
        $this->get($project->path())->assertSee($task['body']);
        $this->assertDatabaseHas('tasks', $task);
        $this->assertInstanceOf(\App\Task::class, $project->fresh()->tasks()->first());
    }

    /** @test */
    public function a_task_required_a_body()
    {
        $project = ProjectSetup::create();

        $task = ['body' => ''];

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', $task)
            ->assertSessionHasErrors(['body']);
        $this->assertDatabaseMissing('tasks', $task);
        
    }

    /** @test */
    public function a_task_can_be_updated_and_body_is_required()
    {
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

        $response->assertRedirect($project->path());

        $task->refresh();
        
        $this->assertEquals('new body', $task->body);
        $this->assertTrue($task->wasChecked());
    }

    /** @test */
    public function only_owner_of_project_can_update_tasks()
    {
        $project = ProjectSetup::withTask(1, ['body' => 'old body'])
            ->create();

        $this->signIn();

        $response = $this->patch($project->tasks[0]->path(), [
            'body' => 'new body',
            'checked' => 'checked'
        ]);

        $response->assertForbidden();    
    }
}

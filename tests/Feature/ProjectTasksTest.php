<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /** @test */
    public function a_gust_can_not_add_task()
    {
        $project = factory(Project::class)->create();
        $task = ['body' => $this->faker()->sentence()];

        $this->post($project->path().'/tasks', $task)->assertRedirect('/login');
    }

    /** @test */
    public function only_owner_of_project_can_add_tasks()
    {
        //some refactoring can do here
        $ownerUser = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $ownerUser->id]);
        $task = ['body' => $this->faker()->sentence()];

        $this->actingAs($anotherUser)
            ->post($project->path().'/tasks', $task)
            ->assertForbidden();
        $this->assertDatabaseMissing('tasks', $task);
    }

    /** @test */
    public function a_project_can_has_task()
    {
        $this->withoutExceptionHandling();
        //Given
        $user = $this->signIn();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);

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
        $user = factory(User::class)->create();

        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $task = ['body' => ''];

        $this->actingAs($user)
            ->post($project->path().'/tasks', $task)
            ->assertSessionHasErrors(['body']);
        $this->assertDatabaseMissing('tasks', $task);
        
    }

    /** @test */
    public function a_task_can_be_updated_and_body_is_required()
    {
        $this->signIn();
        $project = Auth::user()->projects()->create(factory(Project::class)->raw());
        $task = $project->addTask(['body' => 'old body']);
        
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
        $this->signIn();
        $project = Auth::user()->projects()->create(factory(Project::class)->raw());
        
        $task = $project->addTask(['body' => 'old body']);
        
        $this->signIn();
        $response = $this->patch($task->path(), [
            'body' => 'new body',
            'checked' => 'checked'
        ]);

        $response->assertForbidden();    
    }
}

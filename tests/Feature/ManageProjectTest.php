<?php

namespace Tests\Feature;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ManageProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();
        //dd(ProjectSetup::raw());
        $attributes = ProjectSetup::raw([], $this->faker()->sentence(6));

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get(Project::first()->path())
            ->assertSee($attributes['title']);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectSetup::create();
        $this->signIn($project->owner);

        $this->get($project->path().'/edit')
            ->assertSee($project->title);
        
        $newAtt = ProjectSetup::raw([], null);

        $this->patch($project->path(), $newAtt)
            ->assertRedirect($project->path()); 
        
        $this->assertDatabaseHas('projects', $newAtt);
    }
    
    /** @test */
    public function a_user_can_update_project_notes_it_can_be_empty()
    {
        $this->withoutExceptionHandling();

        $project = ProjectSetup::create();
        $this->signIn($project->owner);

        $data = ['notes' => 'Changed Note'];

        $this->patch($project->path().'/notes', $data)
            ->assertRedirect($project->path());
            
        $this->assertEquals($data['notes'], $project->fresh()->notes);

        $this->patch($project->path().'/notes', ['notes' => '']);
        $this->assertEquals('', $project->fresh()->notes);
    }



    /** @test */
    public function a_user_can_only_update_their_project()
    {
        $user = $this->signIn();
        $project = ProjectSetup::create();

        $this->get($project->path().'/edit')->assertForbidden();
        $this->patch($project->path(), ProjectSetup::raw())->assertForbidden();
        $this->patch($project->path().'/notes')->assertForbidden();
    }

    /** @test */
    public function guests_can_not_manage_project()
    {
        $project = ProjectSetup::create();

        //cant see create page
        $this->get('/projects/create')->assertRedirect('/login');

        //cant create
        $this->post('/projects', $project->toArray())->assertRedirect('/login');

        //cant update 
        $this->get($project->path().'/edit')->assertRedirect('/login');
        $this->patch($project->path(), ProjectSetup::raw())->assertRedirect('/login');
        $this->patch($project->path().'/notes', ['notes' => 'Changed'])->assertRedirect('/login');

        // cant delete
        $this->delete($project->path())->assertRedirect('/login');
        
        //cant see one (show)
        $this->get($project->path())->assertRedirect('/login');

        //cant see all (index)
        $this->get('/projects')->assertRedirect('/login');
    }

    /** @test */
    public function a_title_is_required_to_create_and_update_a_project()
    {
        $user = $this->signIn();
        $attributes = ProjectSetup::raw(['title' => null]);

        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    
        $project = ProjectSetup::belongsTo($user)->create();
        $this->patch($project->path(), $attributes)->assertSessionHasErrors(['title']);  
    }
    
    /** @test */
    public function a_description_is_required_to_create_and_update_a_project()
    {
        $user = $this->signIn();
        $attributes = ProjectSetup::raw(['description' => null]);

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);

        $project = ProjectSetup::belongsTo($user)->create();
        $this->patch($project->path(), $attributes)->assertSessionHasErrors(['description']);  
    }

    /** @test */
    public function a_user_can_see_accessible_projects()
    {
        
        $user = $this->signIn();

        $theirProj = ProjectSetup::belongsTo($user)->create();
        $otherProj = ProjectSetup::create();

        //can see their project
        $this->get($theirProj->path())
            ->assertSee($theirProj->title);

        //can see their projects
        $this->get('/projects')
            ->assertSee($theirProj->title)
            ->assertDontSee($otherProj->title);
        
        //can not see project of other user
        $this->get($otherProj->path())
            ->assertForbidden();    
    }

    /** @test */
    public function user_can_delete_his_owne_project()
    {
        $project1 = ProjectSetup::create();
        $project2 = ProjectSetup::create();
        $this->signIn($project1->owner);

        $this->delete($project2->path())
            ->assertForbidden();

        $this->delete($project1->path())
            ->assertRedirect(action('ProjectsController@index'));

        $this->assertDatabaseHas('projects', $project2->fresh()->toArray());
        $this->assertDatabaseMissing('projects', $project1->toArray());
    }

}

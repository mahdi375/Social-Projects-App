<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_project_has_path()
    {
        $project = factory(Project::class)->create();

        $this->assertEquals("/projects/{$project->id}", $project->path());
    }

    /** @test */
    public function a_project_has_owner()
    {
        $project = factory(Project::class)->create();

        $this->assertInstanceOf(User::class, $project->owner);
    }
}

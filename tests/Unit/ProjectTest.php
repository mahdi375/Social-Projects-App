<?php

namespace Tests\Unit;

use App\Project;
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
}

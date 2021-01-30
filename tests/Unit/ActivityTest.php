<?php

namespace Tests\Unit;

use App\ProjectActivity;
use App\Task;
use Facades\Tests\Setup\ProjectSetup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_task_as_subject()
    {
        $this->withoutExceptionHandling();
        
        $project = ProjectSetup::withTask(1)->create();

        $activity = ProjectActivity::all()->last();
        
        $this->assertInstanceOf(Task::class, $activity->subject);
    }

    /** @test */
    public function it_has_user()
    {
        $this->withoutExceptionHandling();

        $naghee = $this->signIn();
        $project = ProjectSetup::belongsTo($naghee)->create();

        $activity = ProjectActivity::latest()->first();
        $this->assertTrue($activity->user->is($naghee));
    }

}

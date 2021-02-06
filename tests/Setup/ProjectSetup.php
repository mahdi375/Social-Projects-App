<?php
namespace Tests\Setup;

use App\Project;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

class ProjectSetup
{
    protected $taskCount = 0;
    protected $taskAttributes = [];
    protected $user = null;

    public function withTask($count = 1, $taskAttributes = [])
    {
        $this->taskCount = $count;
        $this->taskAttributes = $taskAttributes;

        return $this;
    }

    public function belongsTo(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function raw($attributes = [], $note = false)
    {
        $attributes = Arr::except(factory(Project::class)->raw($attributes), 'owner_id');

        $note === false ?: $attributes['notes'] = $note;

        return $attributes;
    }

    public function create($attributes = [])
    {
        //assigned to given user
        $this->user ? $attributes['owner_id'] = $this->user->id : '';

        //create project
        $project = factory(Project::class)->create($attributes);
        
        // create wanted Tasks
        $this->taskAttributes['project_id'] = $project->id;
        $this->taskCount ? factory(Task::class, $this->taskCount)->create($this->taskAttributes) : '';

        // because we call it as facade, it remember States
        $this->clearStates();

        return $project;
    }

    public function rawTask($attributes = [], bool $withProjectId = false)
    {
        return Arr::except(factory(Task::class)->raw($attributes), $withProjectId ? [] : 'project_id');
    }

    protected function clearStates()
    {
        $this->user = null;
        $this->taskCount = 0;
        $this->taskAttributes = [];
    }
}


?>
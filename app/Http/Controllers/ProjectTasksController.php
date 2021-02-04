<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);
        $project->addTask($this->validData());
        
        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $project);

        $task->wholeUpdate($this->validData());

        return redirect($project->path());
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('update', $project);
        
        $task->delete();

        return redirect($project->path());
    }

    protected function validData()
    {
        return request()->validate([
            'body' => 'required|string',
            'checked' => 'nullable'
        ]);
    }

}

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
        $task = request()->validate(['body' => 'required']);
        $project->addTask($task);
        
        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        // this queries should placed in model
        $this->authorize('update', $project);
        request()->validate([
            'body' => 'required',
            'checked' => 'sometimes'
        ]);
        $data = [
            'body' => request('body'),
            'checked_at' => request('checked') ? now() : null,
        ];

        $task->update($data);

        return redirect($project->path());
    }

}

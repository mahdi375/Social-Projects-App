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
        if(Auth::user()->isNot($project->owner)){
            abort(403);
        }
        $task = request()->validate(['body' => 'required']);
        $project->addTask(request(['body']));
        
        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        if(Auth::user()->isNot($project->owner)){
            abort(403);
        }
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

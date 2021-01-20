<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $task = request()->validate(['body' => 'required']);

        if(Auth::user()->isNot($project->owner)){
            abort(403);
        }
        $project->addTask(request(['body']));
        
        return redirect($project->path());
    }

}

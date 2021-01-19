<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;
        
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        //validate
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
        
        //persist
        //call relation as method so we can query to db (its a query builder)
        Auth::user()->projects()->create($attributes);

        //redirect
        return redirect('/projects');
    }

    public function show(Project $project)
    {
        if(auth()->user()->id !== $project->owner->id){
            return abort(403);
        }
        return view('projects.show', compact('project'));
    }
}

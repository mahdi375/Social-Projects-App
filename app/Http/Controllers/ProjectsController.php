<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectActivity;
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
            'description' => 'required',
            'notes' => 'nullable|string'
        ]);
        
        //persist
        //call relation as method so we can query to db (its a query builder)
        Auth::user()->projects()->create($attributes);

        //redirect
        return redirect('/projects');
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        $project->load('tasks');
        
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $attributes = request()->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $project->update($attributes);

        return redirect($project->path());
    }

    public function updateNotes(Project $project)
    {
        $this->authorize('update', $project);
        
        $data = request()->validate([
            'notes' => 'nullable|string'
        ]);

        $project->update($data);

        return redirect($project->path());
    }
}

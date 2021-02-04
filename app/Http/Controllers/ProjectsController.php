<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectActivity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->accessibleProjects();
        
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        //call relation as method so we can query to db (its a query builder)
        Auth::user()->addProject($this->validData());

        //redirect
        return redirect('/projects');
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        $project->load('tasks');

        $activities = $project->getActivities()->limit(10)->get();
        
        return view('projects.show', compact('project', 'activities'));
    }

    public function edit(Project $project)
    {
        $this->authorize('destroy', $project);
        
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('destroy', $project);

        $project->update($this->validData());

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

    public function destroy(Project $project)
    {
        $this->authorize('destroy', $project);

        $project->delete();

        return redirect()->route('ProjectsIndex');
    }

    public function invite(Project $project)
    {
        $this->authorize('destroy', $project);
        
        request()->validate([
            'email' => 'required|exists:users,email',
        ]);
        
        $project->invite(User::whereEmail(request('email'))->first());

        return redirect($project->path());
    }

    protected function validData()
    {
        return request()->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'notes' => 'nullable|string'
        ]);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        self::created(function($project){
            $project->recordActivity('created');
        });

        self::updated(function($project){
            $project->recordActivity('updated');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function addTask(array $task)
    {
        return $this->tasks()->create($task);
    }

    public function recordActivity($description)
    {
        return ProjectActivity::create([
            'project_id' => $this->id,
            'description' => $description
        ]);
    }

}

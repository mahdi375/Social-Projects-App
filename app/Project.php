<?php

namespace App;

use App\Traites\RecordActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use RecordActivity;
    
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_member')->withTimestamps();
    }


    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function addTask(array $task)
    {
        return $this->tasks()->create($task);
    }

    public function invite(User $user)
    {
        $this->members()->attach($user);
        
        return $this;
    }

}

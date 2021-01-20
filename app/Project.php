<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $guarded = [];

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


}

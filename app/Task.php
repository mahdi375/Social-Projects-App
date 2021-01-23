<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $dates = ['checked_at'];

    protected $touches = ['project'];

    protected static function boot()
    {
        parent::boot();

        // a way to listen to the model events (created, updated, ...)
        static::created(function($task){
            $task->project->recordActivity('task-added');
        });

        static::deleted(function($task){
            $task->project->recordActivity('task-deleted');
        });
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function path()
    {
        return "{$this->project->path()}/tasks/{$this->id}";
    }

    public function wasChecked()
    {
        return (bool) $this->checked_at;
    }

    /**
     * @param boolean $state
     * @return Task
     */
    public function check($state = true)
    {
        $this->update(['checked_at' => $state ? now() : null]);
        
        $activity = $state ? 'task-checked' : 'task-unchecked';
        $this->project->recordActivity($activity);

        return $this;
    }

    /**
     * @return Task
     */
    public function uncheck()
    {
        return $this->check(false);
    }
}

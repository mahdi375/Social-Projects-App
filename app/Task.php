<?php

namespace App;

use App\Traites\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use RecordActivity;
    protected $guarded = [];

    protected $dates = ['checked_at'];

    protected $touches = ['project'];

    protected static $recordableEvents = ['created', 'deleted'];

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
        $this->recordActivity($activity);

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

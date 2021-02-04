<?php

namespace App;

use App\Traites\RecordActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

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

    public function wholeUpdate($data)
    {
        isset($data['checked']) ? $this->check() : $this->uncheck();

        $this->update(Arr::only($data, 'body'));

        return $this;  
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

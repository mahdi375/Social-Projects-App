<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $dates = ['checked_at'];

    protected $touches = ['project'];

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
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    
    protected $guarded = [];

    protected $casts = [
        'changes' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }
}

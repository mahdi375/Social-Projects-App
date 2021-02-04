<?php
namespace App\Traites;

use App\ProjectActivity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait RecordActivity
{
    
    protected static $old = [];
    

    public static function bootRecordActivity()
    {   
        foreach(self::activityNames() as $activity){
            static::$activity(function($model) use ($activity){
                $model->recordActivity(Str::kebab(class_basename($model)).'-'.$activity);
            });
        }
        
        static::updating(function($model){
            self::$old = $model->getOriginal();
        });
    }

    public static function activityNames(){
        if(!isset(static::$recordableEvents)){
            return ['created', 'updated'];
        }

        return static::$recordableEvents;
    }
    
    public function activities()
    {
        return $this->morphMany(ProjectActivity::class, 'subject')->latest();
    }

    public function recordActivity($description)
    {
        return $this->activities()->create([
            'user_id' => (Auth::user() ?? $this->project->owner ?? $this->owner)->id,
            'project_id' => $this->projectId(),
            'description' => $description,
            'changes' => $this->changes($description),
        ]);
    }

    public function changes($description)
    {
        return  str_contains($description, 'updated') ? 
            [
                'before' => Arr::except(array_diff(self::$old, $this->getAttributes()), ['updated_at']),
                'after' => Arr::except($this->getChanges(), ['updated_at']),
            ] : null ;
    }

    public function projectId()
    {
        return class_basename($this) == 'Project' ? $this->id : $this->project->id;
    }

    
    public function getActivities()
    {
        return class_basename($this) === 'Project' ?
            ProjectActivity::where('project_id', $this->id) :
            $this->activities();
    }
}
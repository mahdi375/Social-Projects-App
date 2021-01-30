<?php
namespace App\Traites;

use App\ProjectActivity;
use Illuminate\Support\Arr;
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
            return ['created', 'deleted', 'updated'];
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
        if(class_basename($this) === 'Project')
        {
            return $this->activities()
                ->orWhere('project_id', $this->id);
        }

        return $this->activities();
    }
}
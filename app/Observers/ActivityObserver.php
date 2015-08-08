<?php

namespace App\Observers;

/**
 * Description of ModelActivityObserver
 *
 * @author absolux
 */
class ActivityObserver {
    
    
    /**
     * creates an activity entry for the created model
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function created($model) {
        return $model->activity()->create(['type' => 'create']);
    }
    
    /**
     * creates an activity entry for each dirty attribute
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function updated($model) {
        $dirty = $model->getDirty();
        
        foreach ($dirty as $key => $value) {
            $data = ['type' => $key, 'note' => $value];
            $model->activity()->create($data);
        }
    }
    
    /**
     * creates an activity entry if the model is trashed, deletes all otherwise
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function deleted($model) {
        if ( method_exists($model, 'trashed') && $model->trashed() ) {
            return $model->activity()->create(['type' => 'archive']);
        }
        
        // force delete a model must delete all its activities
        return $model->activity()->delete();
    }
    
    /**
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function restored($model) {
        return $model->activity()->create(['type' => 'restore']);
    }
}

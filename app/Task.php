<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model implements ActivityInterface {
    
    
    public $timestamps = true;
    
    protected $fillable = ['name', 'status'];
    
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assignees() {
        return $this->belongsToMany(User::class, 'task_user', 'user_id');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }
    
}
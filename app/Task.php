<?php

namespace App;



class Task extends \Illuminate\Database\Eloquent\Model {
    
    
    public $timestamps = false;
    
    protected $fillable = ['name', 'done', 'flag', 'due'];
    
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assigned() {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo('App\Project');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function label() {
        return $this->belongsTo('App\ProjectLabel');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity() {
        return $this->morphMany('App\Activity', 'resource');
    }
}

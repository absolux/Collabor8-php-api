<?php

namespace App;

class Project extends \Illuminate\Database\Eloquent\Model {
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    
    public $timestamps = false;

    protected $fillable = ['name', 'due', 'desc'];
    
    protected $dates = ['deleted_at'];
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function team() {
        return $this->belongsToMany('App\User')->withPivot('role');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function labels() {
        return $this->hasMany('App\ProjectLabel');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks() {
        return $this->hasMany('App\Task');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity() {
        return $this->morphMany('App\Activity', 'resource');
    }
}

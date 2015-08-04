<?php

namespace App;

class Project extends \Illuminate\Database\Eloquent\Model {
    
    
    public $timestamps = false;
    
    protected $softDelete = true;

    protected $fillable = ['name', 'due', 'desc'];
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function team() {
        return $this->belongsToMany('App\User')->withPivot('role');
    }
    
}

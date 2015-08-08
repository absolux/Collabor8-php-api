<?php

namespace App;

class Activity extends \Illuminate\Database\Eloquent\Model {
    
    
    protected $fillable = ['type', 'note'];
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    /**
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource() {
        return $this->morphTo();
    }
    
    public function setUpdatedAt($value) {
        // Do nothing
    }
}

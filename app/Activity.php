<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class Activity extends \Illuminate\Database\Eloquent\Model {
    
    
    protected $fillable = ['type', 'note'];
    
    
    protected static function boot() {
        parent::boot();
        
        static::creating(function(Activity $activity) {
            $activity->user()->associate(Auth::user());
        });
    }
    
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

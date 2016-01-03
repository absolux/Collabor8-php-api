<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model implements ActivityInterface {
    use \Illuminate\Database\Eloquent\SoftDeletes;
    
    
    public $timestamps = true;
    
    protected $fillable = ['name', 'desc'];
    
    protected $dates = ['deleted_at'];
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function people() {
        return $this->belongsToMany(User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author() {
        return $this->belongsTo(User::class);
    }
    
}

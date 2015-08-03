<?php

namespace App;

class Project extends \Illuminate\Database\Eloquent\Model {
    
    
    public $timestamps = false;
    
    protected $softDelete = true;

    protected $fillable = ['name', 'due', 'desc'];
    
    
    
}

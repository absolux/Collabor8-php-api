<?php

namespace App\Extension\JWT\Facades;

use Lcobucci\JWT\Builder;

/**
 * Description of Token
 *
 * @author absolux
 */
class Token {
    
    
    public static function decode() {
        
    }
    
    public static function builder() {
        return new Builder();
    }
}

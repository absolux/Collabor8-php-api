<?php

namespace App\Extension\JWT\Contracts;

use Lcobucci\JWT\Token;

/**
 *
 * @author absolux
 */
interface TokenProvider {
    
    
    /**
     * Creates a new token object
     * 
     * @param string $identifier the user id
     * @param array $claims other public claims
     * @return Token
     */
    function encode($identifier, $claims = []);
    
    /**
     * Parses the token string
     * 
     * @param string $token
     * @return Token
     * @throws \InvalidArgumentException if the token is invalid
     */
    function decode($token);
    
    /**
     * validate a given token object
     * 
     * @param Token $token
     * @return boolean
     */
    function validate(Token $token);
}

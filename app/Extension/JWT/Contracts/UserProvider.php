<?php

namespace App\Extension\JWT\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Lcobucci\JWT\Token;

/**
 *
 * @author absolux
 */
interface UserProvider {
    
    
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return Authenticatable|null
     */
    function retrieveByCredentials(array $credentials);
    
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return Authenticatable|null
     */
    function retrieveById($identifier);
    
    /**
     * Retrieve a user by subject claim from token
     *
     * @param  string $token
     * @return Authenticatable|null
     */
    function retrieveByToken($token);
    
    /**
     * Create a new token from the user identifier
     * 
     * @param Authenticatable $user
     * @param Token
     */
    function refreshToken(Authenticatable $user);
    
    /**
     * Validate a user against the given credentials.
     *
     * @param  Authenticatable  $user
     * @param  array $credentials
     * @return boolean
     */
    function validateCredentials(Authenticatable $user, array $credentials);
    
    
}

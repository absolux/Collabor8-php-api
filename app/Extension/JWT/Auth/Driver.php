<?php

namespace App\Extension\JWT\Auth;

use BadMethodCallException;
use Illuminate\Contracts\Auth\Guard as GuardContract;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Request;
use App\Extension\JWT\Contracts\UserProvider;
use Lcobucci\JWT\Token;

/**
 * Description of JWTGuard
 *
 * @author absolux
 */
class Driver implements GuardContract {
    
    /** 
     * @var Request 
     */
    protected $request;
    
    /** 
     * @var UserProvider 
     */
    protected $provider;
    
    /**
     * @var Authenticatable
     */
    protected $user;
    
    /**
     * @var Token
     */
    protected $token;
    
    /**
     * The user we last attempted to retrieve.
     *
     * @var Authenticatable
     */
    protected $attempted;
    
    /**
     * @var Dispatcher
     */
    protected $events;
    
    /**
     * Indicates if the user was authenticated via json web token
     * 
     * @var boolean 
     */
    protected $viaToken = false;

    
    /**
     * Creates new JWT Guard instance
     * 
     * @param UserProvider $provider
     * @param Request $request
     */
    public function __construct(UserProvider $provider) {
        $this->provider = $provider;
    }
    
    /**
     * Get the current request instance
     * 
     * @return Request
     */
    public function getRequest() {
        return $this->request ?: Request::createFromGlobals();
    }
    
    /**
     * Set the current request instance
     * 
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }
    
    /**
     * Set the user provider instance
     * 
     * @param UserProvider $provider
     */
    public function setProvider(UserProvider $provider) {
        $this->provider = $provider;
    }
    
    /**
     * Get the user provider instance
     * 
     * @return UserProvider
     */
    public function getProvider() {
        return $this->provider;
    }
    
    /**
     * Get the event dispatcher instance.
     * 
     * @return Dispatcher
     */
    function getDispatcher() {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     * 
     * @param Dispatcher $events
     */
    function setDispatcher(Dispatcher $events) {
        $this->events = $events;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array   $credentials
     * @param  boolean $refresh Will refresh the token if true
     * @param  boolean $login
     * @return boolean
     */
    public function attempt(array $credentials = array(), $refresh = false, $login = true) {
        if ( $this->events ) {
            $this->events->fire('auth.attempt', $argv);
        }
        
        $this->attempted = $user = $this->provider->retrieveByCredentials($credentials);
        
        if ( $user && $this->provider->validateCredentials($user, $credentials) ) {
            if ( $login ) {
                $this->login($user, $refresh);
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Attempt to authenticate using HTTP Basic Auth.
     *
     * @param  string  $field
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function basic($field = 'email') {
        throw new BadMethodCallException();
    }

    /**
     * Determine if the current user is authenticated.
     * 
     * @return boolean
     */
    public function check() {
        if ( $this->user ) {
            return true;
        }
        
        $token = $this->parseToken();
        $user = $this->provider->retrieveByToken($token);
        
        $this->viaToken = !is_null($user);
        
        if ( $user ) {
            $this->login($user);
            return true;
        }
    
        return false;
    }
    
    /**
     * Search the token from request header or query string
     * 
     * @return string|null
     */
    protected function parseToken() {
        $header = config('jwt.header');
        $query = $this->getRequest()->get('token');
        
        return $this->getRequest()->headers->get($header, $query);
    }

    /**
     * Determine if the current user is a guest.
     * 
     * @return boolean
     */
    public function guest() {
        return !$this->check();
    }

    /**
     * Log a user into the application.
     *
     * @param  Authenticatable $user
     * @param  boolean $refresh Refresh or not the token
     * @return void
     */
    public function login(Authenticatable $user, $refresh = false) {
        if ( $refresh ) {
            $this->refreshToken($user);
        }

        if ( $this->events ) {
            $this->events->fire('auth.login', $argv);
        }
        
        $this->setUser($user);
    }
    
    /**
     * Create new token from user model
     * 
     * @param Authenticatable $user
     */
    protected function refreshToken(Authenticatable $user) {
        $this->token = $this->provider->refreshToken($user);
    }
    
    /**
     * Log the given user ID into the application.
     *
     * @param  mixed   $id
     * @param  boolean $refresh refresh token or not
     * @return Authenticatable
     */
    public function loginUsingId($id, $refresh = false) {
        $user = $this->provider->retrieveById($id);
        
        if ( is_null($user) ) {
            return null;
        }
        
        $this->login($user, $refresh);
        
        return $user;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout() {
        $this->user = null;
        $this->token = null;
    }

    /**
     * Log a user into the application without refreshing tokens
     * 
     * @param  array $credentials
     * @return boolean
     */
    public function once(array $credentials = array()) {
        if ( $this->validate($credentials) ) {
            $this->setUser($this->attempted);
            
            return true;
        }
        
        return false;
    }

    /**
     * Perform a stateless HTTP Basic login attempt.
     *
     * @param  string  $field
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onceBasic($field = 'email') {
        throw new BadMethodCallException();
    }

    /**
     * Get the currently authenticated user.
     * 
     * @return Authenticatable|null
     */
    public function user() {
        return $this->getUser();
    }
    
    /**
     * Set the current user
     * 
     * @param Authenticatable $user
     */
    public function setUser(Authenticatable $user) {
        $this->user = $user;
    }
    
    /**
     * Get the authenticated user
     * 
     * @return Authenticatable
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return boolean
     */
    public function validate(array $credentials = array()) {
        return $this->attempt($credentials, false, false);
    }

    /**
     * return true if the user is retreived by token, or false otherwise
     * 
     * @return boolean
     */
    public function viaRemember() {
        return $this->viaToken;
    }

    /**
     * Get token object for authenticated user
     * 
     * @return Token
     */
    public function getToken() {
        return $this->token;
    }
}

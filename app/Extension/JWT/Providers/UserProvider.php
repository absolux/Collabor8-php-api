<?php

namespace App\Extension\JWT\Providers;

use App\Extension\JWT\Contracts\UserProvider as ProviderContract;
use App\Extension\JWT\Contracts\TokenProvider as TokenRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Description of JWTUserProvider
 *
 * @author absolux
 */
class UserProvider implements ProviderContract {
    
    
    /**
     * The hasher implementation.
     *
     * @var Hasher
     */
    protected $hasher;
    
    /**
     * The Eloquent user model.
     *
     * @var Model
     */
    protected $model;
    
    /**
     *
     * @var TokenRepository
     */
    protected $repository;


    /**
     * Contructor
     * 
     * @param Hasher $hasher
     * @param TokenRepository $repository
     * @param string $model
     */
    public function __construct(Hasher $hasher, TokenRepository $repository, $model) {
        $this->hasher = $hasher;
        
        $this->repository = $repository;
        
        // create the user model
        $class = '\\'.ltrim($model, '\\');
        $this->model = new $class();
    }
    
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials) {
        $query = $this->model->newQuery();
        
        foreach ( Arr::except($credentials, 'password') as $key => $value ) {
            $query->where($key, $value);
        }
        
        return $query->first();
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier) {
        return $this->model->newQuery()->find($identifier);
    }

    /**
     * Retrieve a user by subject claim from token
     *
     * @param  string $token
     * @return Authenticatable|null
     */
    public function retrieveByToken($token) {
        $id = $this->repository->decode($token)->getClaim('sub');
        
        return $this->retrieveById($id);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  Authenticatable $user
     * @param  array $credentials
     * @return boolean
     */
    public function validateCredentials(Authenticatable $user, array $credentials) {
        $plain = $credentials['password'];
        $hashed = $user->getAuthPassword();
        
        return $this->hasher->check($plain, $hashed);
    }

    /**
     * Create a new token from the user identifier
     * 
     * @param Authenticatable $user
     * @param string
     */
    public function refreshToken(Authenticatable $user) {
        $claims = [
            'name' => $user->name,
            'email' => $user->email,
            'locale' => config('app.locale')
        ];
        
        return (string) $this->repository->encode($user->getAuthIdentifier(), $claims);
    }

    
}

<?php

namespace App\Extension\JWT\Providers;

use Illuminate\Support\ServiceProvider as ProviderContract;
use Illuminate\Support\Facades\Auth;
use App\Extension\JWT\Auth\Driver;

/**
 * Description of JWTServiceProvider
 *
 * @author absolux
 */
class ServiceProvider extends ProviderContract {
    
    
    /**
     * Bootstrap JWT auth driver
     */
    public function boot() {
        Auth::extend('jwt', function($app) {
            return new Driver($this->createUserProvider($app));
        });
    }
    
    /**
     * create a user provider for auth driver
     * 
     * @return UserProvider
     */
    protected function createUserProvider($app) {
        $repositor = new TokenProvider();
        
        return new UserProvider($app['hash'], $repositor, config('auth.model'));
    }
    
    public function register() {
        $config_path = __DIR__ . '/../config.php';
        
        $this->mergeConfigFrom($config_path, 'jwt');
    }
}

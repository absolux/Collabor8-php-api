<?php

use Illuminate\Contracts\Hashing\Hasher;
use App\Extension\JWT\Providers\TokenProvider;
use App\Extension\JWT\Providers\UserProvider;
use App\User;

/**
 * Description of UserProviderTest
 *
 * @author absolux
 */
class UserProviderTest extends \TestCase {
    
    
    public function tearDown() {
        parent::tearDown();
        
        Mockery::close();
    }
    
    
    protected function getMocks() {
        $hasher = Mockery::mock(Hasher::class);
        $repository = Mockery::mock(TokenProvider::class);
        $model = Mockery::mock(User::class);
        
        return [$hasher, $repository, $model];
    }
    
    protected function getProvider($hasher, $repository, $model) {
        return new UserProvider($hasher, $repository, $model);
    }
    
    function testRetrieveByCredentialsReturnsUser() {
        list($hasher, $repository, $model) = $this->getMocks();
        
        $model->shouldReceive('newQuery')->once()->andReturn($model);
        $model->shouldReceive('where')->once()->with('username', 'toto');
        $model->shouldReceive('first')->once()->andReturn('bar');
        
        $provider = $this->getProvider($hasher, $repository, $model);
        $credentials = ['username' => 'toto', 'password' => '*****'];
        
        $this->assertEquals('bar', $provider->retrieveByCredentials($credentials));
    }
    
    function testRetrieveByIdReturnsUser() {
        list($hasher, $repository, $model) = $this->getMocks();
        
        $model->shouldReceive('newQuery')->once()->andReturn($model);
        $model->shouldReceive('find')->once()->with(1)->andReturn('foo');
        
        $provider = $this->getProvider($hasher, $repository, $model);
        
        $this->assertEquals('foo', $provider->retrieveById(1));
    }
    
    
}

<?php

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Extension\JWT\Contracts\UserProvider;
use App\Extension\JWT\Auth\Driver;

/**
 * Description of DriverTest
 *
 * @author absolux
 */
class DriverTest extends \TestCase {
    
    
    public function tearDown() {
        parent::tearDown();
        
        Mockery::close();
    }
    
    function testLoginMethodReturnsAuthenticableInterface() {
        $user = Mockery::mock(Authenticatable::class);
        $driver = $this->getDriver();
        
        $this->withoutEvents();
        $driver->login($user);
        
        $this->assertEquals($user, $driver->user());
    }
    
    function testLoginMethodRefreshesToken() {
        $token = "foo.bar.baz";
        $user = Mockery::mock(Authenticatable::class);
        $driver = $this->getDriver();
        
        $driver->getProvider()
               ->shouldReceive('refreshToken')
               ->once()
               ->with($user)
               ->andReturn($token);
        
        $this->withoutEvents();
        $driver->login($user, true);
        
        $this->assertEquals($token, $driver->getToken());
    }
    
    function testAttemptReturnsTrueForValidCredentials() {
        $user = Mockery::mock(Authenticatable::class);
        $provider = Mockery::mock(UserProvider::class);
        $driver = $this->getMock(Driver::class, ['login'], [$provider]);
        
        $driver->getProvider()
               ->shouldReceive('retrieveByCredentials')
               ->once()
               ->with(['foo'])
               ->andReturn($user);
        
        $driver->getProvider()
               ->shouldReceive('validateCredentials')
               ->once()
               ->with($user, ['foo'])
               ->andReturn(true);
        
        $this->withoutEvents();
        $driver->expects($this->once())->method('login')->with($this->equalTo($user));
        
        $this->assertTrue($driver->attempt(['foo']));
    }
    
    function testCheckMethodReturnsFalseWhenInvalidTokenProvided() {
        $token = 'foo.bar.baz';
        $provider = Mockery::mock(UserProvider::class);
        $driver = $this->getMock(Driver::class, ['parseToken', 'login'], [$provider]);
        
        $driver->getProvider()
               ->shouldReceive('retrieveByToken')
               ->once()
               ->with($token)
               ->andReturn(null);
        
        $driver->expects($this->once())->method('parseToken')->willReturn($token);
        $driver->expects($this->never())->method('login');
        
        $this->assertFalse($driver->check());
    }
    
    function testIsAuthedReturnsTrueWhenUserIsNotNull() {
        $user = Mockery::mock(Authenticatable::class);
        $driver = $this->getDriver();
        
        $driver->setUser($user);
        
        $this->assertTrue($driver->check());
        $this->assertFalse($driver->guest());
    }
    
    /**
     * @return Guard
     */
    protected function getDriver() {
        return new Driver(Mockery::mock(UserProvider::class));
    }
}

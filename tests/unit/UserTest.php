<?php

use App\User;

/**
 * Description of UserTest
 *
 * @author absolux
 */
class UserTest extends \TestCase {
    
    
    /**
     * 
     * @return User
     */
    function testCreateUserViaMoodelFactory() {
        $user = factory(User::class)->make();
        
        $this->assertInstanceOf(User::class, $user);
    }
    
    function testCreateAdminUserViaModelFactory() {
        $admin = factory(User::class, 'admin')->make();
        
        $this->assertInstanceOf(User::class, $admin);
        $this->assertEquals('admin', $admin->role);
    }
}
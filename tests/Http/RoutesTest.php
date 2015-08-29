<?php

use App\User;
use Illuminate\Support\Facades\Hash;

/**
 * Description of RoutesTest
 *
 * @author absolux
 */
class RoutesTest extends \TestCase {
    
    
    public function setUp() {
        parent::setUp();
        
        factory(User::class)->create([
            'email' => 'foo@bar.com',
            'password' => Hash::make('secret'),
        ]);
    }
    
    function testValidCredentialsReturnsToken() {
        $header = config('jwt.header');
        $credentials = ['email' => 'foo@bar.com', 'password' => 'secret'];
        
        $this->post('authenticate', $credentials);
        
        $this->assertResponseOk();
        $this->assertTrue($this->response->headers->has($header));
    }
    
    function testInvalidCredentialsReturnsUnauthorizedResponse() {
        $credentials = ['email' => 'foo@bar.com', 'password' => 'baz'];
        
        $this->post('authenticate', $credentials)->seeStatusCode(401);
    }
}

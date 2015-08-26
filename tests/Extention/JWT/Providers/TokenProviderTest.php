<?php

use App\Extension\JWT\Providers\TokenProvider;
use App\Extension\JWT\Contracts\TokenProvider as TokenContract;
use Lcobucci\JWT\Token;

/**
 * Description of TokenProviderTest
 *
 * @author absolux
 */
class TokenProviderTest extends \TestCase {
    
    
    function testShouldReturnsTokenProviderInstance() {
        $provider = new TokenProvider();
        
        $this->assertInstanceOf(TokenContract::class, $provider);
        
        return $provider;
    }
    
    /**
     * @depends testShouldReturnsTokenProviderInstance
     */
    function testEncodeReturnsTokenObject(TokenContract $provider) {
        $identifier = 1;
        $token = $provider->encode($identifier, ['name' => 'foo']);
        
        $this->assertInstanceOf(Token::class, $token);
        $this->assertEquals($identifier, $token->getClaim('sub'));
        $this->assertCount(4, $token->getClaims()); // [iat, exp, sub and name]
        $this->assertEquals('foo', $token->getClaim('name'));
    }
    
    function testTokenValidation() {
        $provider = new TokenProvider();
        
        $token = $provider->encode(1);
        
        $this->assertTrue($provider->validate($token));
    }
    
    /**
     * @dataProvider getTokens
     * @param string $token_string
     * @expectedException InvalidArgumentException
     */
    function testDecodeThrowsExceptionWhenInvalidTokenGiven($token_string) {
        $provider = new TokenProvider();
        
        $provider->decode($token_string);
    }
    
    static function getTokens() {
        return [
            ["eyJ0eXAiOiJKV1MiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0NDA2MjI0MDAsImV4cCI6MTQ0MDYyNjAwMCwic3ViIjoiMSJ9.TNzR0xFhLYaft9XOEXTzekvdn86Qt1fd2-fjSrDpRos"], // expired token
            ["eyJ0eXAiOiJKV1MiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0NDA2MjI0MDAsImV4cCI6MTQ0MDYyNjAwMCwic3ViIjoiMSJ9.Yaft9X1fRQtrDpRosdTNz2fjEXTzekOvd0xFhLSn86"], // invalid signature
            ['eyJ0eXAiOiJKV1MiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0NDA2MjI0MDAsImV4cCI6MTQ0MDYyNjAwMCwic3ViIjoiMSJ9'], // invalid format
            ['foo.bar.baz'], // bullshit
        ];
    }
    
    
}

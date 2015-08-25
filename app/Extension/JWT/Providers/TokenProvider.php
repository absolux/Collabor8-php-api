<?php

namespace App\Extension\JWT\Providers;

use InvalidArgumentException;
use App\Extension\JWT\Contracts\TokenProvider as ProviderContract;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Description of TokenProvider
 *
 * @author absolux
 */
class TokenProvider implements ProviderContract {
    
    
    /**
     * Parses the token string
     * 
     * @param string $token
     * @return Token
     * @throws InvalidArgumentException
     */
    public function decode($token) {
        $token = $this->parser()->parse($token);
        
        if (! $this->validate($token) ) {
            throw new InvalidArgumentException();
        }
        
        return $token;
    }

    /**
     * Creates a new token object
     * 
     * @param string $identifier the user id
     * @param array $claims other public claims
     * @return Token
     */
    public function encode($identifier, $claims = []) {
        // set the user id as token subject
        $builder = $this->builder()->setSubject($identifier);
        
        // add public claims if available
        foreach ( $claims as $name => $value ) {
            $builder->set($name, $value);
        }
        
        // sign the token
        $builder->sign(new Sha256(), config('jwt.key'));
        
        return $builder->getToken();
    }

    /**
     * validate a given token object
     * 
     * @param Token $token
     * @return boolean
     */
    public function validate(Token $token) {
        $valid = $token->validate($this->rules());
        
        $verified = $token->verify(new Sha256(), config('jwt.key'));
        
        return $valid && $verified;
    }

    /**
     * @return Builder
     */
    protected function builder() {
        $builder = new Builder();
        $time = time();
        
        $builder->setIssuedAt($time)
                ->setExpiration($time + config('jwt.ttl'));
        
        return $builder;
    }
    
    /**
     * @return ValidationData
     */
    protected function rules() {
        $validation = new ValidationData();
        
        return $validation;
    }
    
    /**
     * @return Parser
     */
    protected function parser() {
        return new Parser();
    }
}

<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');
        $token = auth()->login($user);
        
        return $token;
    }
}

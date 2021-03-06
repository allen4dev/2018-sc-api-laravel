<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_can_register_to_the_app()
    {
        $this->withoutExceptionHandling();

        $credentials = [
            'email' => 'allen@example.test',
            'password' => 'secret',
            'username' => 'Allen'
        ];

        $this->json('POST', '/api/auth/register', $credentials)
            ->assertJsonStructure([ 'data' => [
                'type',
                'id',
                'attributes' => [
                    'token'
                ]
            ]]);

        $this->assertDatabaseHas('users', [
            "username" => $credentials['username'],
            "email" => $credentials['email'],
        ]);
    }

    /** @test */
    public function a_user_can_be_logged_in_with_his_credentials()
    {
        $this->withoutExceptionHandling();

        $credentials = [
            'email' => 'allen@example.test',
            'password' => 'secret',
            'username' => 'Allen Walker'
        ];

        $this->json('POST', '/api/auth/register', $credentials);
        
        $this->json('POST', '/api/auth/login', [
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ])
        ->assertStatus(200)
        ->assertJsonStructure([ 'data' => [
            'type',
            'id',
            'attributes' => [ 'token' ]
        ]]);
    }
}

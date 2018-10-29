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
        $credentials = [
            "email" => "allen@example.test",
            "password" => "secret",
            "username" => "Allen"
        ];

        $this->json('POST', '/api/auth/register', $credentials)
            ->assertStatus(201)
            ->assertJsonStructure([ "data" => [
                "attributes" => [
                    "id",
                    "token"
                ]
            ]]);
        
        $this->assertDatabaseHas('users', [
            "username" => $credentials['username'],
            "email" => $credentials['email'],
        ]);
    }
}

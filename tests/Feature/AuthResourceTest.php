<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use JWTAuth;

use App\User;

class AuthResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_contain_a_type_id_and_attributes_under_a_data_object()
    {
        $this->withoutExceptionHandling();

        $credentials = [
            'email' => 'allen@example.test',
            'password' => 'secret',
            'username' => 'Allen'
        ];

        $response = $this->json('POST', '/api/auth/register', $credentials);

        $user = User::first();

        $response->assertJsonStructure(['data' => [
            'type',
            'id',
            'attributes' => [
                'token'
            ]
        ]]);

        $response->assertJson(['data' => [
            'type' => 'auth',
            'id'   => (string) $user->id,
        ]]);
    }
}